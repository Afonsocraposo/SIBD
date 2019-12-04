<?php

class Calendar
{

    /**
     * Constructor
     */

    public function __construct()
    {
        $this->db = new Database();
        $this->dbh = $this->db->connect();
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
        date_default_timezone_set('Europe/London');
    }

    /********************* PROPERTY ********************/
    private $dayLabels = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");

    private $currentYear = 0;

    private $currentMonth = 0;

    private $currentDay = 0;

    private $actualDate = null;

    private $currentDate = null;

    private $daysInMonth = 0;

    private $naviHref = null;

    private $number = 8;

    private $VAT;

    public function setVAT($VAT)
    {
        $this->VAT = $VAT;
    }

    /********************* PUBLIC **********************/

    /**
     * print out the calendar
     */
    public function show()
    {
        $query_doctors = "SELECT COUNT(*) as c FROM doctor";
        $stmt = $this->dbh->prepare($query_doctors);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the doctors");
        } else {
            if ($stmt->rowCount() > 0) {
                $doctors = $stmt->fetch();
                $this->number = 8 * intval($doctors["c"]);
            }
        }

        $year  = null;

        $month = null;

        if (null == $year && isset($_GET['year'])) {

            $year = $_GET['year'];
        } else if (null == $year) {

            $year = date("Y", time());
        }

        if (null == $month && isset($_GET['month'])) {

            $month = $_GET['month'];
        } else if (null == $month) {

            $month = date("m", time());
        }

        $this->currentYear = $year;

        $this->currentMonth = $month;

        $this->actualDate = date('Y-m-d', time());

        $this->actualDay = date("d", time());

        $this->daysInMonth = $this->_daysInMonth($month, $year);

        $content = '<div id="calendar">' .
            '<div class="box">' .
            $this->_createNavi() .
            '</div>' .
            '<div class="box-content">' .
            '<ul class="label">' . $this->_createLabels() . '</ul>';
        $content .= '<div class="clear"></div>';
        $content .= '<ul class="dates">';

        $weeksInMonth = $this->_weeksInMonth($month, $year);
        // Create weeks in a month
        for ($i = 0; $i < $weeksInMonth; $i++) {

            //Create days in a week
            for ($j = 1; $j <= 7; $j++) {
                $content .= $this->_showDay($i * 7 + $j);
            }
        }

        $content .= '</ul>';

        $content .= '<div class="clear"></div>';

        $content .= '</div>';

        $content .= '</div>';

        return $content;
    }

    /********************* PRIVATE **********************/
    /**
     * create the li element for ul
     */
    private function _showDay($cellNumber)
    {

        if ($this->currentDay == 0) {

            $firstDayOfTheWeek = date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-01'));

            if (intval($cellNumber) == intval($firstDayOfTheWeek)) {

                $this->currentDay = 1;
            }
        }

        if (($this->currentDay != 0) && ($this->currentDay <= $this->daysInMonth)) {

            $this->currentDate = date('Y-m-d', strtotime($this->currentYear . '-' . $this->currentMonth . '-' . ($this->currentDay)));

            $cellContent = $this->currentDay;

            $this->currentDay++;
        } else {

            $this->currentDate = null;

            $cellContent = null;
        }

        $opacity = 1;

        $query_count = "SELECT COUNT(*) as c FROM appointment WHERE date_timestamp LIKE CONCAT(?,'%')";
        $stmt = $this->dbh->prepare($query_count);
        $stmt->bindParam(1, $this->currentDate);
        if (!$stmt->execute()) {
            print("Something went wrong when fetching the count");
        } else {
            if ($stmt->rowCount() > 0) {
                $appointments = $stmt->fetch();
            }
        }



        $opacity = 1;
        if ($appointments != null) {
            if ($appointments["c"] != 0) {
                $opacity =  1 - intval($appointments["c"]) / $this->number;
            }
        }

        if ($cellContent == null) {
            $color = "rgb(255,255,255)";
        } else {
            if (((strtotime($this->actualDate) == strtotime($this->currentDate)) && (intval(date('H', time())) < 16)) || (strtotime($this->actualDate) < strtotime($this->currentDate))) {
                $color = "rgba(0,255,0,$opacity)";
            } else {
                $color = "rgba(150,150,150,$opacity)";
            }
        }
        return "<li onclick=\"location.href='" . $this->db->url() . "appointment.php?date=" . $this->currentDate . (($this->VAT != null) ? "&client=" . $this->VAT : "") . "'\" " . 'style="background-color:' . $color . '" id="li-' . $this->currentDate . '" class="' . ($cellNumber % 7 == 1 ? ' start ' : ($cellNumber % 7 == 0 ? ' end ' : ' ')) . ($cellContent == null ? 'mask' : 'number') . '">' . $cellContent . '</li></a>';
    }

    /**
     * create navigation
     */
    private function _createNavi()
    {

        $nextMonth = $this->currentMonth == 12 ? 1 : intval($this->currentMonth) + 1;

        $nextYear = $this->currentMonth == 12 ? intval($this->currentYear) + 1 : $this->currentYear;

        $preMonth = $this->currentMonth == 1 ? 12 : intval($this->currentMonth) - 1;

        $preYear = $this->currentMonth == 1 ? intval($this->currentYear) - 1 : $this->currentYear;

        return
            '<div class="header">' .
            '<span class="prev">
                <form action="" method="GET">
                    <input style="display:none" name="month" value="' . sprintf('%02d', $preMonth) . '">
                    <input style="display:none" name="year" value="' . $preYear . '">
                    <button name="" value="" style="font-size:xx-large"><</button>
                </form>
            </span>' .
            '<span class="title">' . date('Y M', strtotime($this->currentYear . '-' . $this->currentMonth . '-1')) . '</span>' .
            '<span class="next">
                <form action="" method="GET">
                    <input style="display:none" name="month" value="' . sprintf('%02d', $nextMonth) . '">
                    <input style="display:none" name="year" value="' . $nextYear . '">
                    <button name="" value="" style="font-size:xx-large">></button>
                </form>
            </div>';
    }

    /**
     * create calendar week labels
     */
    private function _createLabels()
    {

        $content = '';

        foreach ($this->dayLabels as $index => $label) {

            $content .= '<li class="' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</li>';
        }

        return $content;
    }



    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth($month = null, $year = null)
    {

        if (null == ($year)) {
            $year =  date("Y", time());
        }

        if (null == ($month)) {
            $month = date("m", time());
        }

        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month, $year);

        $numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths / 7);

        $monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $daysInMonths));

        $monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));

        if ($monthEndingDay < $monthStartDay) {

            $numOfweeks++;
        }

        return $numOfweeks;
    }

    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth($month = null, $year = null)
    {

        if (null == ($year))
            $year =  date("Y", time());

        if (null == ($month))
            $month = date("m", time());

        return date('t', strtotime($year . '-' . $month . '-01'));
    }
}

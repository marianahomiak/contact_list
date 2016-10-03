        <?php

        require_once (getcwd() . '/connect_db.php');

        session_start();

        function get_contact_list($start_date, $end_date, $start_time, $end_time){
            global $la_dbconn;

        $sql = '
            SELECT
              CONVERT_TZ(m.datecreated, \'-8:00\', \'+2:00\') AS message_date, 
              cont.contactid, 
              cont.emails,
              cont.firstname,
              cont.lastname,
              c.conversationid,
              c.channel_type,
              c.datecreated AS ticket_date_created,
              c.datechanged AS ticket_date_changed,
              c.code 
            FROM `' . LA_DB_PREFIX . 'qu_la_conversations` AS c
                    LEFT JOIN `' . LA_DB_PREFIX . 'qu_la_messages` AS m
                        ON c.conversationid = m.conversationid
                    LEFT JOIN `' . LA_DB_PREFIX . 'qu_la_conversation_users_history` AS cuh
                        ON c.conversationid = cuh.conversationid
                    LEFT JOIN `' . LA_DB_PREFIX . 'qu_la_users` u 
                        ON u.`userid` = cuh.`userid`
                    LEFT JOIN `' . LA_DB_PREFIX . 'qu_la_contacts` cont 
                        ON cont.`contactid` = u.`contactid`
            WHERE 
                    -- c.channel_type = \'I\' AND 
                    m.datecreated >= \'' . $start_date .' '. $start_time . '\' AND
                    m.datecreated <= \'' . $end_date .' '. $end_time .'\' AND
                    (
                    ((HOUR(CONVERT_TZ(m.datecreated, \'-8:00\', \'+2:00\')) > 21) OR
                     (HOUR(CONVERT_TZ(m.datecreated, \'-8:00\', \'+2:00\')) < 3))
                    ) AND
                    -- c.conversationid IN (\'ee110f0f\', \'aa120eec\', \'7389e208\') AND
                    m.rtype = \'M\' AND
                    m.userid = c.ownerid  AND
                    u.rtype = \'A\' AND 
                    cuh.rstatus = \'J\' AND
                    cont.contactid = \'82375305\'
            GROUP BY c.conversationid
            ORDER BY m.datecreated
            LIMIT 3000
                ';

        $query = mysqli_query($la_dbconn, $sql);

        if ($query) {
            echo "<table border = '1' align = 'center'>";
            echo '<tr>';

            foreach ($query->fetch_assoc() as $key=>$value){
                echo '<td align = "center" bgcolor = "#d3d3d3"><span style = "font-weight:bold">' . $key . '</span></td>' ;
            }

            echo '</tr>';
            $i=0;

            while($row = $query->fetch_assoc())
            {
                if($i%2==0){
                    echo '<tr bgcolor = "#f0ffff">';
                } else {
                    echo '<tr bgcolor = "#dcdcdc">';
                }
                foreach($row as $key=>$value){
                    echo '<td >' . $value . '</td>';
                }
                echo '</tr>';
                $i++;
            }
        }
    }

        function get_data($name){

            if(empty($_REQUEST[$name])&&(!empty($_SESSION[$name]))) {
                return $_SESSION[$name];
            } else if (empty($_SESSION[$name]) && (empty($_REQUEST[$name]))) {

                switch ($name) {
                    case 'start_date':
                        return date("Y-m-1",strtotime("-1 month "));
                        break;
                    case 'end_date':
                        return date("Y-m-t",strtotime("-1 month "));
                        break;
                    case 'start_time':
                        return date("H:m:s",'21:00:00');
                        break;
                    case 'end_time':
                        return date("H:m:s",'03:00:00');
                        break;
                }

            } else if(!empty($_REQUEST[$name])){
                return $_REQUEST[$name];
            }
        }
    ?>

    <div style="width: 100%; text-align: center;">
        <form method="POST">
            Date from:&nbsp;<input type = "date" name = "start_date" id = "start_date" value = "<?php
            $name = 'start_date';
            echo get_data($name);
            ?>" />

            Date to:&nbsp;<input type = "date" name = "end_date" id = "end_date" value = "<?php
            $name = 'end_date';
            echo get_data($name);
            ?>" />

            Hour from:&nbsp;<input type = "time" name = "start_time" id = "start_time" value = "<?php
            $name = 'start_time';
            echo get_data($name);
            ?>" />

            Hour to:&nbsp;<input type = "time" name = "end_time" id = "end_time" value = "<?php
            $name = 'end_time';
            echo get_data($name);
            ?>" />

            <input type = "submit" name = "submit" value = "Search" />
        </form>
    </div>

    <?php

    if(isset($_REQUEST['submit'])) {

        if (isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date'])) {
            $_SESSION['start_date'] = $_REQUEST['start_date'];
        }

        if (isset($_REQUEST['end_date']) && !empty($_REQUEST['end_date'])) {
            $_SESSION['end_date'] = $_REQUEST['end_date'];
        }

        if (isset($_REQUEST['start_time']) && !empty($_REQUEST['start_time'])) {
            $_SESSION['start_time'] = $_REQUEST['start_time'];
        }

        if (isset($_REQUEST['end_time']) && !empty($_REQUEST['end_time'])) {
            $_SESSION['end_time'] = $_REQUEST['end_time'];
        }

        get_contact_list($_SESSION['start_date'],$_SESSION['end_date'], $_SESSION['start_time'], $_SESSION['end_time']);
    }

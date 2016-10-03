    <?php

    require_once (getcwd() . '/connect_db.php');
    require_once (getcwd(). '/get_data.php');

    session_start();

    ?>

    <div style="width: 100%; text-align: center;">
            <form method="POST">
                Date from:&nbsp;<input type="text" name="start_date" id="start_date" value="<?php
                $name = 'start_date';
                echo get_data($name);
                ?>" />

                Date to:&nbsp;<input type="text" name="end_date" id="end_date" value="<?php
                $name = 'end_date';
                echo get_data($name);
                ?>" />

                Hour from:&nbsp;<input type="text" name="start_time" id="start_time" value="<?php
                $name = 'start_time';
                echo get_data($name);
                ?>" />

                Hour to:&nbsp;<input type="text" name="end_time" id="end_time" value="<?php
                $name = 'end_time';
                echo get_data($name);
                ?>" />

                <input type="submit" name="submit" value="Search" />
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

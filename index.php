<?php

/*
* How to run this utility
* $cd assignment
* $php index.php {file_name}
* Report will be exported in exportSalartReport folder. If the file is already exists then it will give you an error.
* Note: No need to mention file_name with .csv
*/

	require_once 'controller/calculateSalaryReport.php';

	$fileName = $argv[1];

	$salaryReport = new calculateSalaryReport($fileName);
	// This will calculate the salary and bonus for all the rest of the month in current year and 
	// export it to the exportSalartReport folder
	$salaryReport -> processCurrentMonth();

?>
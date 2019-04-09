<?php

class calculateSalaryReport{
	
	private $filename;
	
	public function __construct($filename = null){
		$this -> fileName = $filename;		
	}
	
	public function processCurrentMonth(){
		$fileName = $this->fileName . ".csv";
		$filePath = dirname(__FILE__) . '/../exportSalaryReport/' . $fileName;
		
		if(file_exists($filePath)) {
			echo 'Error: File is already exist : ' . $fileName . PHP_EOL;
			exit;
		}
		
		
		$fileHandle = fopen($filePath, 'w+');
		fputcsv($fileHandle, array('Month Name', 'Salary Date', 'Bonus Date'),';');
		$bonusDate = $this->getBonusDates();
		$salaryDate = $this->getSalaryDates();
		
		$finalReport = array();
		foreach ($salaryDate as $key => $value) {
			$finalReport[$key]['month'] = $key;
			$finalReport[$key]['salary'] = $value;
		}
		foreach ($bonusDate as $key => $value) {
			$finalReport[$key]['month'] = $key;
			$finalReport[$key]['bonus'] = $value;
		}
		foreach ($finalReport as $reportData) {
			fputcsv($fileHandle, $reportData);
		}
		fclose($fileHandle);
		
	    echo 'Salary Payment Date Report Generated in file : ' . $filePath . PHP_EOL;
	}
	
	/*
	* To get the list of bonus dates for all months
	*/
	protected function getBonusDates()
	{
		$bonusDate = array();
		
		$currentMonth = date("n");
		$currentYear = date("Y");
		while ( $currentMonth <= 12 ) {
			$processingDate = date('d-m-Y', strtotime("15-".$currentMonth."-".$currentYear));
			$processingDate = $this->getValidBonusDate($processingDate);
			if(strtotime($processingDate) >= strtotime(date("d-m-Y"))) {
				$monthName = date("F",strtotime($processingDate));
				$bonusDate[$monthName] = $processingDate;
			}
			$currentMonth++;
		}
		
		return $bonusDate;
	}
	
	/*
	* To get the list of salary dates for all months
	*/
	protected function getSalaryDates()
	{
		$salaryDate = array();
		//To check for another year starting from January
		$currentMonth = date("n");
		$currentYear = date("Y");
		
		while ( $currentMonth <= 12 ) {
			$lastDate = date('t', strtotime("1-".$currentMonth."-".$currentYear));
			$processingDate = date('d-m-Y', strtotime($lastDate."-".$currentMonth."-".$currentYear));
			$processingDate = $this->getValidSalaryDate($processingDate);
			if(strtotime($processingDate) >= strtotime(date("d-m-Y"))) {
				$monthName = date("F",strtotime($processingDate));
				$salaryDate[$monthName] = $processingDate;
			}
			$currentMonth++;
		}
		return $salaryDate;
	}
	
	/*
	* To validate bonus date against weekend & retrieve next Wednesday
	*/
	protected function getValidBonusDate($processingDate)
	{
		$day = date('l', strtotime($processingDate));
		switch ($day) {
			case 'Saturday':
				$processingDate = date('d-m-Y', strtotime($processingDate . ' +4 day'));
				break;
			
			case 'Sunday':
				$processingDate = date('d-m-Y', strtotime($processingDate . ' +3 day'));
				break;
		}
		return $processingDate;
	}
	/*
	* To validate salary date against weekend & retrieve previous workday
	*/
	protected function getValidSalaryDate($processingDate)
	{
		$day = date('l', strtotime($processingDate));
		switch ($day) {
			case 'Saturday':
				$processingDate = date('d-m-Y', strtotime($processingDate.'-1 day'));
				break;
			
			case 'Sunday':
				$processingDate = date('d-m-Y', strtotime($processingDate.'-2 days'));
				break;
		}
		return $processingDate;
	}

}


?>

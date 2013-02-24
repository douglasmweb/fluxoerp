<?
class Zend_View_Helper_Dataface
{
    public $view;
    private $resultado;

    public function dataface($data)
    {
        $data_final = '';
        
        $timeNow = new Zend_Date();
        $timeThen = new Zend_Date($data, Zend_Date::ISO_8601);
        $difference = $timeNow->sub($timeThen);
        $dataFinal = '';
        if($timeNow->isToday()){
            $dataFinal = $this->_friendlySeconds($difference->toValue());
        } else {
            $dataFinal = $timeThen;
        }
        return $dataFinal;

    }

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

function _friendlySeconds($allSecs) {
    $seconds = $allSecs % 60; $allMinutes = ($allSecs - $seconds) / 60;
    $minutes = $allMinutes % 60; $allHours = ($allMinutes - $minutes) / 60;
    $hours =  $allHours % 24; $allDays = ($allHours - $hours) / 24;
    
    $timeFinal = 'Há ';
    
    if($allDays > 0)$timeFinal .= $allDays . " dia".($allDays==1?'':'s');
    if($hours > 0 AND $allDays == 0)  $timeFinal .= $hours . " hora".($hours==1?'':'s');
    if($minutes > 0 AND $hours == 0 AND $allDays ==0)$timeFinal .= $minutes . " minuto".($minutes==1?'':'s');
    if($seconds > 0 AND $minutes == 0 AND $hours == 0 AND $allDays ==0)$timeFinal .= $seconds . " segundo".($seconds==1?'':'s');
    
    return $timeFinal;
}


}

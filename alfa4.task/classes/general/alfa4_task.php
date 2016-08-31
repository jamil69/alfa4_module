<?
  CModule::IncludeModule("tasks");

  class Alfa4TaskManager {

     const DATE_FORMAT = "%d-%m-%Y";

     const DATE_FORMAT_TASK = "%d/%m/%Y";
     
     const DEADLINE_START_TASK = 14;

     const BEBUG_LOG_PATH = '/task_log.txt';   

     const MODULE_ID = 'alfa4.task'; 

     const GROUP_ID = 12;

     function __construct() {}

     public static function setTaskForEmployee() {

       global $APPLICATION,$USER;

       if(!$USER->IsAdmin()) {

          return;
       }

       $arFilter = array("!UF_CHECK_DAY"=>"");

       $arParams["SELECT"] = array("UF_CHECK_DAY","ID","NAME","LAST_NAME");

       $arRes = CUser::GetList(($by="id"), ($order="desc"), $arFilter,$arParams);

       while($ufield = $arRes->Fetch()) {

         if(self::getDateDiff($ufield["UF_CHECK_DAY"]) <= Alfa4TaskManager::DEADLINE_START_TASK && 
            self::IsTaskDateExpires($ufield["UF_CHECK_DAY"])) {

           $user_full_name = $ufield['NAME'].' '.$ufield['LAST_NAME'];

           if(self::setTask($ufield["UF_CHECK_DAY"],$user_full_name)) {

             self::logTask("Задача поставлена ".$user_full_name);

           } else {
                
             if($e = $APPLICATION->GetException())

                self::logTask("Error: ".$e->GetString());  

          }

         } else {

           self::logTask("Дата задачи не подошла"); 
        }

      }
    }

    private static function getDateDiff($str_date) {

      $today_date = new DateTime(strftime(Alfa4TaskManager::DATE_FORMAT));

      $user_task_date = new DateTime(strftime(Alfa4TaskManager::DATE_FORMAT,strtotime($str_date)));

      $interval = $today_date->diff($user_task_date);

      return  $interval->d;

    }

    private static function setTask($date,$user_name) {

      $task_fileds = array(
        "TITLE"           => self::getOptions('TITLE_0'),
        "DESCRIPTION"     => "Проверить сотрудника: ".$user_name,
        "RESPONSIBLE_ID"  => self::getOptions('RESPONSIBLE_0'),
        "CREATED_BY"      => self::getOptions('CREATED_BY_0'),
        "START_DATE_PLAN" => $date,
        "DEADLINE"        => $date,
        "GROUP_ID"        => Alfa4TaskManager::GROUP_ID,
        "AUDITORS"        => self::getOptions('AUDITORS_0'),
        "ACCOMPLICES"     => self::getOptions('ACCOMPLICES_0')
     );

     $obTask = new CTasks;
  
     if($task_ID = $obTask->Add($task_fileds)) {

        return true;

     }
     
     return false;
    
    }

    private static function IsTaskDateExpires($date) {
 
     $obTask = new CTasks;

     $res = $obTask->GetList(Array("DEADLINE" => "ASC"), Array("DEADLINE"=>$date));  

     if(!$arTask = $res->Fetch()) {

         return true;

     }

     return false;     

    }

    private static function getOptions($key) {

      $params = array("TITLE_0","CREATED_BY_0","RESPONSIBLE_0","ACCOMPLICES_0","AUDITORS_0");  

      $arOptions = array();
 
      foreach($params as $code) {

        $value = COption::GetOptionString(Alfa4TaskManager::MODULE_ID,$code);

         if(is_array($unsrz_value = unserialize($value))) {

            $arOptions[$code] = $unsrz_value;

         } else {

            $arOptions[$code] = $value;

        }
      }
       
      return $arOptions[$key];

    }

    private static function logTask($mess) {
   
       $f = fopen($_SERVER['DOCUMENT_ROOT'].Alfa4TaskManager::BEBUG_LOG_PATH,"a+");

       fwrite($f,$mess."\r\n".date("d.m.Y H:i:s"));

       fclose($f);
       
    }
    
  }
?>
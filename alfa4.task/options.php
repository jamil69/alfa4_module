<?
$module_id = 'alfa4.task';
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/include.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/CModuleOptions.php');
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/options.php');

$showRightsTab = true;

$arTabs = array(
   array(
      'DIV' => 'edit1',
      'TAB' => 'Настройки',
      'ICON' => '',
      'TITLE' => 'Настройки'
   )
);

$arGroups = array(
   'MAIN' => array('TITLE' => GetMessage("TABS_0"), 'TAB' => 0)
);
 
 $users = array("REFERENCE"=>array(),"REFERENCE_ID"=>array());
 $filter = Array();
 $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter); 

 while($arUser = $rsUsers->GetNext()){ 
      $users["REFERENCE"][] = $arUser['NAME'].' '.$arUser['LAST_NAME'];
      $users["REFERENCE_ID"][] = $arUser['ID'];
 }
 
$arOptions = array(
   'TITLE_0' => array(
      'GROUP' => 'MAIN',
      'TITLE' => GetMessage('TITLE'),
      'TYPE' => 'STRING',
      'SORT' => '0',
   ),
   'CREATED_BY_0' => array(
      'GROUP' => 'MAIN',
      'TITLE' => GetMessage("CREATED_BY"),
      'TYPE' => 'SELECT',
       VALUES => $users,
      'SORT' => '1',
  
   ),
  'RESPONSIBLE_0' => array(
      'GROUP' => 'MAIN',
      'TITLE' => GetMessage("RESPONSIBLE"),
      'TYPE' => 'SELECT',
       VALUES => $users,
      'SORT' => '1',
  
   ),
   'ACCOMPLICES_0' => array(
      'GROUP' => 'MAIN',
      'TITLE' => GetMessage("ACCOMPLICES"),
      'TYPE' => 'MSELECT',
       VALUES => $users,
      'SORT' => '2',
  
   ),
   'AUDITORS_0' => array(
      'GROUP' => 'MAIN',
      'TITLE' => GetMessage("AUDITORS"),
      'TYPE' => 'MSELECT',
       VALUES => $users,
      'SORT' => '3',
  
   ),
);

$opt = new CModuleOptions($module_id, $arTabs, $arGroups, $arOptions, $showRightsTab);
$opt->ShowHTML();
?>



<?php 
namespace App\Helpers;
use Config;

class Template {
    public static function showButtonFilter ($controllerName, $itemsStatusCount, $currentFilterStatus, $paramsSearch , $currentFilterCategory = null) { // $currentFilterStatus active inactive all
        $xhtml = null;
        $tmplStatus = Config::get('zvn.template.status');
   
        if (count($itemsStatusCount) > 0) {
            array_unshift($itemsStatusCount , [
                'count'   => array_sum(array_column($itemsStatusCount, 'count')),
                'status'  => 'all'
            ]);
  
            foreach ($itemsStatusCount as $item) {  // $item = [count,status]
    
                $statusValue = !empty($item['status']) ? $item['status'] : $item['contact']  ;  // active inactive block

                $statusValue = array_key_exists($statusValue, $tmplStatus ) ? $statusValue : 'default';

                $currentTemplateStatus = $tmplStatus[$statusValue]; // $value['status'] inactive block active
                $link = ($controllerName !== 'contact') ? route($controllerName) . "?filter_status=" .  $statusValue . "&filter_category=" . $currentFilterCategory : route($controllerName) . "?filter_contact=" .  $statusValue ;

                if($paramsSearch['value'] !== ''){
                    $link .= "&search_field=" . $paramsSearch['field'] . "&search_value=" .  $paramsSearch['value'] . "&filter_category=" . $currentFilterCategory;
                }
               
                $class  = ($currentFilterStatus == $statusValue) ? 'btn-danger' : 'btn-info';
                $xhtml  .= sprintf('<a href="%s" type="button" class="btn %s">
                                    %s <span class="badge bg-white">%s</span>
                                </a>', $link, $class, $currentTemplateStatus['name'], $item['count']);
            }
        }

        return $xhtml;
    }
    public static function showAreaSearch ($controllerName, $paramsSearch) { 
        $xhtml = null;
        $tmplField         = Config::get('zvn.template.search');
        $fieldInController = Config::get('zvn.config.search');

        $controllerName = (array_key_exists($controllerName, $fieldInController)) ? $controllerName : 'default';
        $xhtmlField = null;

        foreach($fieldInController[$controllerName] as $field)  {// all id
            $xhtmlField .= sprintf('<li><a href="#" class="select-field" data-field="%s">%s</a></li>', $field, $tmplField[$field]['name']);
        }
       
        $searchField = (in_array($paramsSearch['field'],  $fieldInController[$controllerName] )) ? $paramsSearch['field'] : "all";

        $xhtml = sprintf('
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle btn-active-field" data-toggle="dropdown" aria-expanded="false">
                        %s <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        %s
                    </ul>
                </div>
                <input type="text" name="search_value" value="%s" class="form-control" >
                <input type="hidden" name="search_field" value="%s">
                <span class="input-group-btn">
                    <button id="btn-clear-search" type="button" class="btn btn-success" style="margin-right: 0px">Xóa tìm kiếm</button>
                    <button id="btn-search" type="button" class="btn btn-primary">Tìm kiếm</button>
                </span>
            </div>', $tmplField[$searchField]['name'], $xhtmlField, $paramsSearch['value'], $searchField);
        return $xhtml;
    }
    public static function showItemHistory ($by, $time) {
        $xhtml = sprintf(
            '<p><i class="fa fa-user"></i> %s</p>
            <p><i class="fa fa-clock-o"></i> %s</p>', $by, date(Config::get('zvn.format.short_time'), strtotime($time)) );
        return $xhtml;
    }
    public static function showItemStatus ($controllerName, $id, $statusValue) {
        $tmplStatus = Config::get('zvn.template.status');
        $statusValue        = array_key_exists($statusValue, $tmplStatus ) ? $statusValue : 'default';
        $currentTemplateStatus = $tmplStatus[$statusValue];
        $link          = route($controllerName . '/status', ['status' => $statusValue, 'id' => $id]);

        $xhtml = sprintf(
            '<a  data-url="%s" type="button"  name ="change-ajax-status" class="btn btn-round %s  ">%s</a>', $link , $currentTemplateStatus['class'], $currentTemplateStatus['name']  );
        return $xhtml;
    }
    public static function showItemIsHome ($controllerName, $id, $isHomeValue ) {
        $tmplIsHome = Config::get('zvn.template.is_home');
        $isHomeValue        = array_key_exists($isHomeValue, $tmplIsHome ) ? $isHomeValue : 'yes';
        $currentTemplateIsHome = $tmplIsHome[$isHomeValue];
        $link          = route($controllerName .  '/' . 'isHome', ['isHome' => $isHomeValue, 'id' => $id]);
  
        $xhtml = sprintf(
            '<a  data-url="%s" type="button"  name ="change-ajax-isHome" class="btn btn-round %s">%s</a>', $link , $currentTemplateIsHome['class'], $currentTemplateIsHome['name']  );
        return $xhtml;
    }
    public static function showItemOrdering ($controllerName, $id, $orderingValue) {
        $link    = route($controllerName . '/ordering'  , [ 'ordering' => "new_value" ,  'id' => $id]);
        $xhtml   = sprintf(
           '<input type="text" data-url="%s" style="width: 100px; text-align:center;"  name="change-ajax-ordering" class="form-control" value="%s">',$link ,$orderingValue);
       return $xhtml;
    }
    public static function showItemThumb ($controllerName, $thumbName, $thumbAlt) {
        $xhtml = sprintf(
            '<img src="%s" alt="%s" class="zvn-thumb">', asset("images/$controllerName/$thumbName")  , $thumbAlt );
        return $xhtml;
    }
    public static function showItemThumbUpload ($thumbName, $thumbAlt) {
        $xhtml = null;
        foreach ($thumbName as $value) {
            $xhtml .= sprintf(
                '<img src="%s" alt="%s" style="height: 50px" class="zvn-thumb">', asset("uploads/$value")  , $thumbAlt );
        }
       
        return $xhtml;
    }
    public static function showAttribute ($attribute) {
        $xhtml = null;
        foreach ($attribute as $key => $value) {
            $tmp =  implode(',', $value['value']);
            $xhtml .= $value['name'] .': ' . $tmp . '<br>';
        }
        return $xhtml;
    }
    public static function showButtonAction ($controllerName, $id) {
        $tmplButton   = Config::get('zvn.template.button');
        $buttonInArea = Config::get('zvn.config.button');

        $controllerName = (array_key_exists($controllerName, $buttonInArea)) ? $controllerName : "default";
        $listButtons    = $buttonInArea[$controllerName]; // ['edit', 'delete']

        $xhtml = '<div class="zvn-box-btn-filter">';

        foreach ($listButtons as $btn) {
            $currentButton = $tmplButton[$btn];

            $link = route($controllerName . $currentButton['route-name'], ['id' => $id] );
            $xhtml .= sprintf(
                '<a href="%s" type="button" class="btn btn-icon %s" data-toggle="tooltip" data-placement="top" 
                    data-original-title="%s">
                    <i class="fa %s"></i>
                </a>', $link, $currentButton['class'], $currentButton['title'], $currentButton['icon']);
        }

        $xhtml .= '</div>';

        return $xhtml;
    }
    public static function showDatetimeFrontend($dateTime){
        return date_format(date_create($dateTime), Config::get('zvn.format.short_time'));
    }
    public static function showContent($content, $length, $prefix = '...'){
        $prefix = ($length == 0) ? '' : $prefix;
        $content = str_replace(['<p>', '</p>'], '', $content);
        return preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $length)) . $prefix;
    }
    public static function showItemContact($controllerName, $id, $statusValue) {
        $tmplStatus = Config::get('zvn.template.contact');
        $statusValue        = array_key_exists($statusValue, $tmplStatus ) ? $statusValue : 'default';
        $currentTemplateStatus = $tmplStatus[$statusValue];
        $link          = route($controllerName . '/contact', ['contact' => $statusValue, 'id' => $id]);
        $xhtml = sprintf(
            '<a  data-url="%s" type="button"  name ="change-ajax-contact" class="btn btn-round %s  ">%s</a>', $link , $currentTemplateStatus['class'], $currentTemplateStatus['name']  );
        return $xhtml;
    }
    public static function showItemContactDisabled($controllerName, $id, $statusValue) {
        $tmplStatus = Config::get('zvn.template.contact');
        $statusValue        = array_key_exists($statusValue, $tmplStatus ) ? $statusValue : 'default';
        $currentTemplateStatus = $tmplStatus[$statusValue];
        $link          = route($controllerName . '/contact', ['contact' => $statusValue, 'id' => $id]);
        $xhtml = sprintf(
            '<p   readonly="readonly" class="btn btn-round %s  ">%s</p>' , $currentTemplateStatus['class'], $currentTemplateStatus['name']  );
        return $xhtml;
    }
    public static function nameCoupon($name){ 
        if($name == 'percent'){
            $xhtml = 'Phần trăm' ;
        }else{
            $xhtml = 'Tiền mặt';
        }
        return $xhtml ;
    }
    public static function valueCoupon($name , $value){
        if($name == 'percent'){
            $xhtml = $value . "%" ;
        }else{
            $xhtml = number_format($value) . " VNĐ" ;
        }
        return $xhtml ;
    }
    public static function getName($items){
      
        $level  = $items['depth'] ;
        $name   = $items['name'];
        $xhtml  = null;
        for ($i=2; $i <= $level; $i++) { 
            $xhtml .= '|---';
        }
        return  $xhtml .  $name;
    }
    // DANH MỤC NESTED
    public static function showSelectBoxCategoryNested($item, $itemsCategories , $value = null, $link = null){
       
        $value = ($value == null) ? 'parent_id' : $value ;
        $xhtml = '<select data-url="'.$link.'" class="custom-select form-control col-md-6 col-xs-12" name="'.$value.'">';
        foreach ($itemsCategories as $key => $items){
           if(!empty($item['id']) && $item['id'] == $items['id']) continue;
           $name       = Template::getName($items);
           if($item['category_id'] == $items['id' ]  || $item['parent_id'] == $items['id' ]) {
            //  continue;
                $xhtml .= sprintf('<option selected value="%s" >%s</option>', $items['id'], $name);
           }else{
                $xhtml .= sprintf('<option value="%s" >%s</option>', $items['id'], $name);
           }
        }
        $xhtml .= '</select>';
        return $xhtml;
    }
    // SẢN PHẨM NESTED
    public static function showSelectBoxProductNested($item, $itemsCategories, $value = null,$fillter_category = null){
    
        $xhtml = '<select  name="'.$value.'" class="custom-select form-control col-md-6 col-xs-12" name="'.$value.'"><option value="default">-- Chọn danh mục --</option>';
        foreach ($itemsCategories as $key => $items){
 
           if(!empty($item['id']) && $item['id'] == $items['id']) continue;
           $name       = Template::getName($items);
           if($item['category_id'] == $items['id']) {
                $xhtml .= sprintf('<option selected value="%s" >%s</option>', $items['id'], $name);
           }elseif($items['id'] == $fillter_category){
                $xhtml .= sprintf('<option selected value="%s" >%s</option>', $items['id'], $name);
           }
           else{
                $xhtml .= sprintf('<option value="%s" >%s</option>', $items['id'], $name);
           }
        }
        $xhtml .= '</select>';
        return $xhtml;
    }

    public static function getStarFeedBack($params){
        
        $start = 100 ;
        if($params == 4) $start = 80 ;
        if($params == 3) $start = 60 ;
        if($params == 2) $start = 40 ;
        if($params == 1) $start = 10 ;
        return   $start;
    }
  
}

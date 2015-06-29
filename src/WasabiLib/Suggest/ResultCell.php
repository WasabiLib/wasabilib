<?php
/**
 * @link https://github.com/WasabilibOrg/wasabilib
 * Copyright 2015 www.wasabilib.org
 * @license Apache License, Version 2.0
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace WasabiLib\Suggest;


class ResultCell {

    protected $textLabel = null;
    protected $subTitle;
    protected $image = null;
    protected $cssClass;
    protected $badge = null;
    protected $action = null;
    protected $data= null;
    protected $ajaxElement = "ajax_element";
    protected $elementId = "";

    public function __construct($textLabel = null){
        $this->textLabel = $textLabel;
    }

    /**
     * @return mixed
     */
    public function getTextLabel()
    {
        return $this->textLabel;
    }

    /**
     * @param mixed $textLabel
     */
    public function setTextLabel($textLabel)
    {
        $this->textLabel = $textLabel;
    }

    /**
     * @return mixed
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param mixed $subTitle
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($imageSrc)
    {
        $image = "<img src='".$imageSrc."' class='wasabi-suggest-image'>";
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getElementId()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setElementId($elementId)
    {
        $this->elementId = $elementId;
    }

    /**
     * You can use any icon class you want e.g. fa or glyphicons
     * it replaces the img tag
     * @param $iconClassName
     */
    public function setIcon($iconClassName){
        $icon = "<i class='".$iconClassName."'></i>";
        $this->image = $icon;
    }

    public function createListElement(){
        $uniqueId = $this->elementId != "" ? $this->elementId : uniqid('SE');
        $action = "href='";
        $action .= $this->action ? $this->action : "javascript:void(0)";
        !$this->action ? $this->ajaxElement = "" : false;
        $action .= "'";
        $cell = "<a id='".$uniqueId."' ".$action." class='".$this->ajaxElement." list-group-item' ".$this->data.">
         ".$this->image."
        ".$this->badge."


             <h4 class='list-group-item-heading'>".$this->textLabel."</h4>";
        if($this->subTitle)
            $cell.="<p class='list-group-item-text'>".$this->subTitle."</p>";
            
            $cell .="</a>\n";

        return $cell;
    }

    public function addCssClass($className){
        $this->cssClass = $className;
    }

    public function setBadge($badge){
        $this->badge = "<span class='badge'>".$badge."</span>";
    }

    /**
     * @param $action
     */
    public function setAction($action, $ajax = true){
        $this->action = $action;
        if(!$ajax)
            $this->ajaxElement = false;
    }

    /**
     * @param associative $array
     */
    public function setData($array){
        $json = json_encode($array);
        $this->data = "data-json='".$json."'";
    }

    public function setAjaxElement($bool){
        if($bool) $this->ajaxElement = "ajax_element";
        else $this->ajaxElement = false;
    }

}

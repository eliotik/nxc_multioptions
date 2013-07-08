<?php

class NXCMultiOptionsUtils
{
    function NXCMultiOptionsUtils()
    {
        $this->Operators = array( 'nmo_in_array', 'nmo_get_html_line' );
    }
    
    function operatorList() { return $this->Operators; }
    function namedParameterPerOperator() { return true; }
    
    function namedParameterList()
    {
        return array( 
            'nmo_in_array' => array(
                'needle' => array( 'type' => 'string', 'required' => true, 'default' => '' ),
                'haystack' => array( 'type' => 'array', 'required' => true, 'default' => array() ) 
            ),
            'nmo_get_html_line' => array(
                'classdata' => array( 'type' => 'array', 'required' => true, 'default' => array() ),
                'linedata' => array( 'type' => 'array', 'required' => true, 'default' => array() )
            )
        );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'nmo_get_html_line':
                $classdata = $namedParameters['classdata'];
                $linedata = $namedParameters['linedata'];

                $result = '
                     
                    <div class="product-option-block">
                        <div class="product-color-item oslo-bors-item"></div>
                        <div class="product-color-item oslo-axess-item"></div>
                        <div class="product-color-item burgundy-item"></div>
                    </div>
                    <div class="product-option-block">
                        <div class="product-color-item oslo-bors-item"></div>
                        <div class="product-color-item nordic-abm-item"></div>
                        <div class="product-color-item burgundy-item"></div>
                    </div>
                    <div class="product-option-block">
                        <div class="product-color-item oslo-bors-item"></div>
                        <div class="product-color-item oslo-connect-item"></div>
                    </div>
                    
                ';
                
                if (!empty($linedata))
                {
                    $result = '';
                    foreach ($linedata as $index => $multioption)
                    {
                        $optionsCount = count($multioption['optionlist']);
                        if ($optionsCount > 0)
                        {
                            switch($index)
                            {
                                case 1:
                                    $result .= '<div class="product-option-block">';
                                    foreach ($multioption['optionlist'] as $option)
                                    {
                                        $correctedIndex = $index-1;
                                        switch($this->getOptionIndex($correctedIndex,$classdata,$option))
                                        {
                                            case 0:
                                                $result .= '<div class="product-color-item oslo-bors-item check-item "></div>';
                                            break;
                                            case 1:
                                                if ($optionsCount == 1)
                                                {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                } elseif (($optionsCount == 2) and !$this->hasOptionIndex($index,$classdata,$linedata,0)) {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                }                                            
                                                $result .= '<div class="product-color-item oslo-axess-item check-item"></div>';
                                            break;
                                            case 2:
                                                if ($optionsCount == 1)
                                                {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                    $result .= '<div class="product-color-item oslo-axess-item"></div>';
                                                } elseif (($optionsCount == 2) and !$this->hasOptionIndex($index,$classdata,$linedata,1)) {
                                                    $result .= '<div class="product-color-item oslo-axess-item"></div>';
                                                }
                                                $result .= '<div class="product-color-item burgundy-item check-item"></div>';
                                            break;
                                            default:
                                                $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                            break;
                                        }
                                    }
                                    $result .= '</div>';
                                    if (!isset($linedata[2]) and !isset($linedata[3]))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    }
                                    if (isset($linedata[2]) and empty($linedata[2]['optionlist']) and 
                                        isset($linedata[3]) and empty($linedata[3]['optionlist']))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    }
                                break;
                                case 2:

                                    if (!isset($linedata[1]))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    }
                                    if (isset($linedata[1]) and empty($linedata[1]['optionlist']))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    }

                                    $result .= '<div class="product-option-block">';
                                    foreach ($multioption['optionlist'] as $option)
                                    {                                
                                        $correctedIndex = $index-1;
                                        switch($this->getOptionIndex($correctedIndex,$classdata,$option))
                                        {
                                            case 0:
                                                $result .= '<div class="product-color-item oslo-bors-item check-item "></div>';
                                            break;
                                            case 1:
                                                if ($optionsCount == 1)
                                                {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                } elseif (($optionsCount == 2) and !$this->hasOptionIndex($index,$classdata,$linedata,0)) {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                }  
                                                $result .= '<div class="product-color-item nordic-abm-item check-item"></div>';
                                            break;
                                            case 2:
                                                if ($optionsCount == 1)
                                                {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                    $result .= '<div class="product-color-item nordic-abm-item"></div>';
                                                } elseif (($optionsCount == 2) and !$this->hasOptionIndex($index,$classdata,$linedata,1)) {
                                                    $result .= '<div class="product-color-item nordic-abm-item"></div>';
                                                }
                                                $result .= '<div class="product-color-item burgundy-item check-item"></div>';
                                            break;
                                            default:
                                                $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                            break;
                                        }
                                    }
                                    $result .= '</div>';

                                    if (!isset($linedata[3]))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    }
                                    if (isset($linedata[3]) and empty($linedata[3]['optionlist']))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    }
                                break;
                                case 3:

                                    if (!isset($linedata[1]) and !isset($linedata[2]))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    } elseif (isset($linedata[1]) and !isset($linedata[2])) {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    } elseif (isset($linedata[1]) and empty($linedata[1]['optionlist']) and
                                        isset($linedata[2]) and empty($linedata[2]['optionlist']))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    } elseif (isset($linedata[1]) and !empty($linedata[1]['optionlist']) and
                                        isset($linedata[2]) and empty($linedata[2]['optionlist']))
                                    {
                                        $result .= '<div class="product-option-block">';
                                        $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                        $result .= '</div>';
                                    }

                                    $result .= '<div class="product-option-block">';
                                    foreach ($multioption['optionlist'] as $option)
                                    {                                
                                        $correctedIndex = $index-1;
                                        switch($this->getOptionIndex($correctedIndex,$classdata,$option))
                                        {
                                            case 0:
                                                $result .= '<div class="product-color-item oslo-bors-item check-item "></div>';
                                            break;
                                            case 1:
                                                if ($optionsCount == 1)
                                                {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                } elseif (($optionsCount == 2) and !$this->hasOptionIndex($index,$classdata,$linedata,0)) {
                                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                                }  
                                                $result .= '<div class="product-color-item oslo-connect-item check-item "></div>';
                                            break;
                                            default:
                                                $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                            break;
                                        }                                
                                    }
                                    $result .= '</div>';
                                break;
                                default:
                                    $result .= '<div class="product-option-block">';
                                    $result .= '<div class="product-color-item oslo-bors-item"></div>';
                                    $result .= '</div>';
                                break;
                            }
                        }
                    }
                }
                $operatorValue = $result;
            break;
            case 'nmo_in_array':
                $needle = $namedParameters['needle'];
                $haystack = $namedParameters['haystack'];
                if (empty($haystack))
                {
                    $operatorValue = false;
                } else {
                    $found = false;
                    foreach($haystack as $item)
                    {
                        if ($item['option_id'] == $needle)
                        {
                            $found = true;
                            break;
                        }
                    }
                    $operatorValue = $found;
                }
            break;
        }
    }

    private function getOptionIndex($multioptionIndex, $classdata, $option)
    {
        if (empty($classdata) or !is_array($classdata) or 
            !isset($classdata[$multioptionIndex]) or empty($classdata[$multioptionIndex]['options'])) return false;
        
        foreach($classdata[$multioptionIndex]['options'] as $index => $optiondata )
        {
            if (intval($optiondata['option_id']) == intval($option['option_id'])) return $index;
        }
        
        return false;
    }
    
    var $Operators;

    public function hasOptionIndex($multioptionIndex, $classdata, $linedata, $optionIndex) 
    {
        $multioptionIndexCorrected = $multioptionIndex-1;
        if (empty($linedata) or !is_array($linedata) or 
            empty($classdata) or !is_array($classdata) or                 
            !isset($classdata[$multioptionIndexCorrected]) or empty($classdata[$multioptionIndexCorrected]['options']) or
            !isset($linedata[$multioptionIndex]) or empty($linedata[$multioptionIndex]['optionlist'])) return false;
        
        $optionId = false;
        foreach($classdata[$multioptionIndexCorrected]['options'] as $index => $optiondata )
        {
            if ($index == $optionIndex)
            {
                $optionId = $optiondata['option_id'];
                break;
            }
        }
        if ($optionId === false) return $optionId;
        
        foreach($linedata[$multioptionIndex]['optionlist'] as $index => $optiondata )
        {
            if ($optiondata['option_id'] == $optionId) return true;
        }
        
        return false;        
    }    
}

?>

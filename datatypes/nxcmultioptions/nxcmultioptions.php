<?php

class NXCMultiOptions
{
    /*!
     Initializes with empty multioption list.
    */
    function NXCMultiOptions( /*$name*/ )
    {
        //$this->Name = $name;
        $this->Options = array();
        $this->MultiOptionCount = 0;
        $this->OptionCounter = 0;
    }

    /*!
      Adds an Multioption named \a $name
      \param $name contains the name of multioption.
      \param $multiOptionPriority is stored for displaying the array in order.
      \param $defaultValue is stored to display the options by default.
      \return The ID of the multioption that was added.
    */
    function addMultiOption( $name, $multiOptionPriority/*, $defaultValue = ''*/ )
    {
        $this->MultiOptionCount += 1;
        $this->Options[$this->MultiOptionCount] = array( "id" => $this->MultiOptionCount,
                                                         "name" => $name,
                                                         'priority'=> $multiOptionPriority,
                                                         //"default_option_id" => $defaultValue,
                                                         'optionlist' => array() );
        return $this->MultiOptionCount;
    }

    /*!
      Adds an Option to multioption \a $name
      \param $newID is the element key value to which the new option will be added.
      \param $optionValue is the original value to display for users.
      \param $optionAdditionalPrice is a price value that is used to store price of the option values.
    */
    function addOption( $newID, $OptionID, $optionValue/*, $optionAdditionalPrice*/ )
    {
        $key = count( $this->Options[$newID]['optionlist'] ) + 1;
        if ( strlen( $OptionID ) == 0 )
        {
            $this->OptionCounter += 1;
            $OptionID = $this->OptionCounter;
        }
        $this->Options[$newID]['optionlist'][] = array( "id" => $key,
                                                        "option_id" => $OptionID,
                                                        "value" => $optionValue/*,
                                                        'additional_price' => $optionAdditionalPrice*/ );
    }

    /*!
     Sorts the current multioption on the basis of it's priority value.
     After softing array it calles the function changeMultiOptionID,
     which again rearragnes the current priority value to 1,2,3......etc.
    */
    function sortMultiOptions()
    {
        usort( $this->Options, create_function( '$a, $b', 'if ( $a["priority"] == $b["priority"] ) { return 0; } return ( $a["priority"] < $b["priority"] ) ? -1 : 1;' ) );
        $this->changeMultiOptionID();
    }

    /*!
      Finds the largest \c option_id among the options and sets it as \a $this->OptionCounter
    */
    function resetOptionCounter()
    {
        $maxValue = 0;
        foreach ( $this->Options as $optionList )
        {
            foreach ( $optionList['optionlist'] as $option )
            {
                if ( $maxValue < $option['option_id'] )
                {
                    $maxValue = $option['option_id'];
                }
            }
        }
        $this->OptionCounter = $maxValue;
    }

    /*!
      Change the id of multioption in ascending order.
    */
    function changeMultiOptionId()
    {
        $i = 1 ;
        foreach ( $this->Options as $key => $opt )
        {
            $this->Options[$key]['id'] = $i++;
        }
        $this->MultiOptionCount = $i - 1;
    }

    /*!
      Remove MultiOption from the array.
      After calling this function all the options associated with that multioption will be removed.
      This function also calles to changeMultiOption to reset the key value of multioption array.
      \param $array_remove is the array of those multiOptions which is selected to remove.
      \sa removeOptions()
    */
    function removeMultiOptions( $array_remove, $simple = false )
    {
        foreach ( $array_remove as $id )
        {
            if ($simple)
                unset( $this->Options[ $id ] );
            else    
                unset( $this->Options[ $id - 1 ] );
        }
        $this->Options = array_values( $this->Options );
        $this->changeMultiOptionId();
    }

    /*!
      Remove Options from the multioption.
      This function first remove selected options and then reset the key value if all options for that multioption.
      \param $arrayRemove is a list of all array elements which is selected to remove from the multioptions.
      \param $optionId is the key value if multioption from which it is required to remove the options.
      \sa removeMultiOptions()
    */
    function removeOptions( $arrayRemove, $optionId, $simple = false)
    {
        //var_dump($this->Options[$optionId]);
        foreach ( $arrayRemove as $id )
        {
            /*echo "[$id]";
            if ($simple)
                unset( $this->Options[$optionId]['optionlist'][$id] );
            else*/
            unset( $this->Options[$optionId]['optionlist'][$id - 1] );
        }
        $this->Options = array_values( $this->Options );
        $i = 1;
        foreach ( $this->Options[$optionId]['optionlist'] as $key => $opt )
        {
            $this->Options[$optionId]['optionlist'][$key]['id'] = $i;
            $i++;
        }
    }

    /*!
     \return list of supported attributes
    */
    function attributes()
    {
        return array( /*'name',*/
                      'multioption_list' );
    }

    /*!
      Returns true if object have an attribute.
      The valid attributes are \c name and \c multioption_list.
      \param $name contains the name of attribute
    */
    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /*!
    Returns an attribute. The valid attributes are \c name and \c multioption_list
    \a name contains the name of multioption
    \a multioption_list contains the list of all multioptions.
    */
    function attribute( $name )
    {
        switch ( $name )
        {
            /*case "name" :
            {
                return $this->Name;
            } break;*/

            case "multioption_list" :
            {
                return $this->Options;
            } break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
                return null;
            }break;
        }
    }

    /*!
    Will decode an xml string and initialize the eZ Multi option object.
    If $xmlString is on empty then it will call addMultiOption() and addOption() functions
    to create new multioption else it will decode the xml string.
    \param $xmlString contains the complete data structure for multioptions.
    \sa xmlString()
    */
    function decodeXML( $xmlString )
    {
        $this->OptionCounter = 0;
        $this->Options = array();
        if ( $xmlString != "" )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );

            $root = $dom->documentElement;
            // set the name of the node
            //$this->Name = $root->getElementsByTagName( "name" )->item( 0 )->textContent;
            $this->OptionCounter = $root->getAttribute( "option_counter" );
            $multioptionsNode = $root->getElementsByTagName( "multioptions" )->item( 0 );
            $multioptionsList = $multioptionsNode->getElementsByTagName( "multioption" );
            //Loop for MultiOptions
            foreach ( $multioptionsList as $multioption )
            {
                $newID = $this->addMultiOption( $multioption->getAttribute( "name" ),
                                                $multioption->getAttribute( "priority" )/*,
                                                $multioption->getAttribute( "default_option_id" )*/ );
                $optionNode = $multioption->getElementsByTagName( "option" );
                foreach ( $optionNode as $option )
                {
                    $this->addOption( $newID, $option->getAttribute( "option_id" ), $option->getAttribute( "value" )/*, $option->getAttribute( "additional_price" )*/ );
                }
            }
        }
    }

    /*!
     Will return the XML string for this MultiOption set.
     \sa decodeXML()
    */
    function xmlString()
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( "nxcmultioptions" );
        $root->setAttribute( 'option_counter', $this->OptionCounter );
        $doc->appendChild( $root );

        /*$nameNode = $doc->createElement( 'name' );
        $nameNode->appendChild( $doc->createTextNode( $this->Name ) );
        $root->appendChild( $nameNode );*/

        $multiOptionsNode = $doc->createElement( "multioptions" );
        $root->appendChild( $multiOptionsNode );
        foreach ( $this->Options as $multioption )
        {
            unset( $multioptionNode );
            $multioptionNode = $doc->createElement( "multioption" );
            $multioptionNode->setAttribute( "id", $multioption['id'] );
            $multioptionNode->setAttribute( "name", $multioption['name'] );
            $multioptionNode->setAttribute( "priority", $multioption['priority'] );
            //$multioptionNode->setAttribute( 'default_option_id', $multioption['default_option_id'] );
            foreach ( $multioption['optionlist'] as $option )
            {
                unset( $optionNode );
                $optionNode = $doc->createElement( "option" );
                $optionNode->setAttribute( "id", $option['id'] );
                $optionNode->setAttribute( "option_id", $option['option_id'] );
                $optionNode->setAttribute( "value", $option['value'] );
                //$optionNode->setAttribute( 'additional_price', $option['additional_price'] );
                $multioptionNode->appendChild( $optionNode );
            }
            $multiOptionsNode->appendChild( $multioptionNode );
        }
        $xml = $doc->saveXML();
        return $xml;
    }

    /// \privatesection
    /// Contains the Option name
    //public $Name;
    /// Contains the Options
    public $Options;
    /// Contains the multioption counter value
    public $MultiOptionCount;
    /// Contains the option counter value
    public $OptionCounter;

    public function changeMultiOption($id, $multioptionName) 
    {
        if (isset($this->Options[$id]))
            $this->Options[$id]['name'] = $multioptionName;
    }

    public function changeOption($multioptionId, $id, $value) 
    {
        if (isset($this->Options[$multioptionId]))
            if (isset($this->Options[$multioptionId]['optionlist']) and !empty($this->Options[$multioptionId]['optionlist']) and
                isset($this->Options[$multioptionId]['optionlist'][$id-1]))
            $this->Options[$multioptionId]['optionlist'][$id-1]['value'] = $value;
    }

    public function removeMultiOption($id)
    {
        if (isset($this->Options[$id]))
        {
            unset($this->Options[$id]);
            $this->MultiOptionCount -= 1;
            if ($this->MultiOptionCount < 0) $this->MultiOptionCount = 0;
        }
            
    }

    public function getIndexes() 
    {
        $multioptions = array();
        foreach ($this->Options as $key => $multiOptionData)
        {
            $multioption = array();
            foreach ($multiOptionData['optionlist'] as $optiondata)
            {
                $multioption[] = $optiondata['id'];
            }
            $multioptions[$key] = $multioption;
        }
    }
}
?>

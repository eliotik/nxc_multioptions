<?php

class NXCMultiOptionsType extends eZDataType
{
    const DEFAULT_NAME_VARIABLE = "_nxcmultioptions_default_name_";
    const DATA_TYPE_STRING = "nxcmultioptions";

    /*!
     Constructor to initialize the datatype.
    */
    function NXCMultiOptionsType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "NXC MultiOptions", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input for this datatype.
     \return True if input is valid.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        //echo "validateObjectAttributeHTTPInput-";
        
        $count = 0;
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $http->hasPostVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $multioptionIDArray = $http->postVariable( $base . "_data_multioption_id_" . $contentObjectAttribute->attribute( "id" ) );

            foreach ( $multioptionIDArray as $id )
            {
                $multioptionName = $http->postVariable( $base . "_data_multioption_name_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id );
                $optionIDArray = $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                 ? $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                 : array();
                $optionCountArray = $http->hasPostVariable( $base . "_data_option_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    ? $http->postVariable( $base . "_data_option_option_id_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    : array();
                $optionValueArray = $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    ? $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                    : array();
                /*$optionAdditionalPriceArray = $http->hasPostVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                              ? $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) . '_' . $id )
                                              : array();*/
                for ( $i = 0; $i < count( $optionIDArray ); $i++ )
                {
                    if ( $contentObjectAttribute->validateIsRequired() and !$classAttribute->attribute( 'is_information_collector' ) )
                    {
                        if ( trim( $optionValueArray[$i] ) == "" )
                        {
                            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                                 'The option value must be provided.' ) );
                            return eZInputValidator::STATE_INVALID;
                        }
                        else
                            ++$count;
                    }

                    /*if ( trim( $optionValueArray[$i] ) != "" )
                    {
                        if ( strlen( $optionAdditionalPriceArray[$i] ) && !preg_match( "#^[-|+]?[0-9]+(\.){0,1}[0-9]{0,2}$#", $optionAdditionalPriceArray[$i] ) )
                        {
                            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                                 'The additional price for the multioption value is not valid.' ) );
                            return eZInputValidator::STATE_INVALID;
                        }
                    }*/

                }
            }
        }
        if ( $contentObjectAttribute->validateIsRequired() and
                 !$classAttribute->attribute( 'is_information_collector' ) )
        {
            if ( $count == 0 )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                     'At least one option is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }

            /*$optionSetName = $http->hasPostVariable( $base . "_data_optionset_name_" . $contentObjectAttribute->attribute( "id" ) )
                             ? $http->postVariable( $base . "_data_optionset_name_" . $contentObjectAttribute->attribute( "id" ) )
                             : '';
            if ( trim( $optionSetName ) == '' )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                     'Option set name is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }*/
        }


        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     This function calles xmlString function to create xml string and then store the content.
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        //echo "storeObjectAttribute-";
        $multioption = $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );
    }

    /*!
     \return An NXCMultiOptions object which contains all the option data
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        //echo "objectAttributeContent-";
        $multioption = new NXCMultiOptions( );
        $multioption->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
        return $multioption;
    }

    function isIndexable()
    {
        ////echo "isIndexable-";
        return true;
    }

    /*!
     \return The internal XML text.
    */
    function metaData( $contentObjectAttribute )
    {
        //echo "metaData-";
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        //echo "fetchObjectAttributeHTTPInput-";
        //var_dump($http->attribute('post'));
        $contentObjectAttributeId = $contentObjectAttribute->attribute( "id" );
        $multioptionIDArray = $http->hasPostVariable( $base . "_data_multioption_id_" . $contentObjectAttributeId )
                              ? $http->postVariable( $base . "_data_multioption_id_" . $contentObjectAttributeId )
                              : array();
        $multioption = new NXCMultiOptions( );
        foreach ( $multioptionIDArray as $id )
        {
            $multioptionName = $http->postVariable( $base . "_data_multioption_name_" . $contentObjectAttributeId . '_' . $id );
            $optionIDArray = $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttributeId . '_' . $id )
                             ? $http->postVariable( $base . "_data_option_id_" . $contentObjectAttributeId . '_' . $id )
                             : array();
            //echo '[optionIDArray:]';
            //var_dump($optionIDArray);
            $optionPriority = $http->postVariable( $base . "_data_multioption_priority_" . $contentObjectAttributeId . '_' . $id );
            // check to prevent PHP warning if the default choice is specified (no radio button selected)
            /*if ( $http->hasPostVariable( $base . "_data_radio_checked_" . $contentObjectAttribute->attribute("id") . '_' . $id ) )
                $optionDefaultValue = $http->postVariable( $base . "_data_radio_checked_" . $contentObjectAttribute->attribute("id") . '_' . $id );
            else
                $optionDefaultValue = '';*/
            //$newID = $multioption->addMultiOption( $multioptionName,$optionPriority, $optionDefaultValue );
            $newID = $multioption->addMultiOption( $multioptionName,$optionPriority );

            /*$optionCountArray = $http->hasPostVariable( $base . "_data_option_option_id_" . $contentObjectAttributeId . '_' . $id )
                                ? $http->postVariable( $base . "_data_option_option_id_" . $contentObjectAttributeId . '_' . $id )
                                : array();*/
            /*$optionValueArray = $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttributeId . '_' . $id )
                                ? $http->postVariable( $base . "_data_option_value_" . $contentObjectAttributeId . '_' . $id )
                                : array();*/
            //echo '[optionIDArray:]';
            //var_dump($optionCountArray);
            for ( $i = 0, $len = count( $optionIDArray ); $i < $len; $i++ )
            {
                $multioption->addOption( $newID, $optionIDArray[$i], "" );
                ////echo "[i: $i][newID: {$newID}][optionIDArray: {$optionIDArray[$i]}]";
            }
        }
        //echo $multioption->xmlString();
        
        //$multioption->sortMultiOptions();
        /*echo $multioption->xmlString();
        die('STOP');*/
        $multioption->resetOptionCounter();
        $contentObjectAttribute->setContent( $multioption );
        return true;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        //echo "fetchCollectionAttributeHTTPInput-";
        $multioptionValue = $http->postVariable( $base . "_data_multioption_value_" . $contentObjectAttribute->attribute( "id" ) );
        $collectionAttribute->setAttribute( 'data_int', $multioptionValue );
        return true;
    }

    /*!
     This function performs specific actions.

     It has some special actions with parameters which is done by exploding
     $action into several parts with delimeter '_'.
     The first element is the name of specific action to perform.
     The second element will contain the key value or id.

     The various operation's that is performed by this function are as follow.
     - new-option - A new option is added to a multioption.
     - remove-selected-option - Removes a selected option.
     - new_multioption - Adds a new multioption.
     - remove_selected_multioption - Removes all multioptions given by a selection list
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        //echo "customObjectAttributeHTTPAction-";
        $actionlist = explode( "_", $action );
        if ( $actionlist[0] == "new-option" )
        {
            $multioption = $contentObjectAttribute->content();

            $multioption->addOption( ( $actionlist[1] - 1 ), "", ""/*, ""*/);
            $contentObjectAttribute->setContent( $multioption );
            $contentObjectAttribute->store();
        }
        else if ( $actionlist[0] == "remove-selected-option" )
        {
            $multioption = $contentObjectAttribute->content();
            $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" ) . "_" . $actionlist[1];
            $array_remove = $http->hasPostVariable( $postvarname ) ? $http->postVariable( $postvarname ) : array();
            $multioption->removeOptions( $array_remove, $actionlist[1] - 1 );
            $contentObjectAttribute->setContent( $multioption );
            $contentObjectAttribute->store();
        }
        else
        {
            switch ( $action )
            {
                case "new_multioption" :
                {
                    $multioption = $contentObjectAttribute->content();
                    $newID = $multioption->addMultiOption( "" ,0 );
                    $multioption->addOption( $newID, "", "" );
                    $multioption->addOption( $newID, "" ,"" );
                    $contentObjectAttribute->setContent( $multioption );
                    $contentObjectAttribute->store();
                } break;

                case "remove_selected_multioption":
                {
                    $multioption = $contentObjectAttribute->content();
                    $postvarname = "ContentObjectAttribute" . "_data_multioption_remove_" . $contentObjectAttribute->attribute( "id" );
                    $array_remove = $http->hasPostVariable( $postvarname )? $http->postVariable( $postvarname ) : array();
                    $multioption->removeMultiOptions( $array_remove );
                    $contentObjectAttribute->setContent( $multioption );
                    $contentObjectAttribute->store();
                } break;

                default:
                {
                    eZDebug::writeError( "Unknown custom HTTP action: " . $action, "NXCMultiOptionsType" );
                } break;
            }
        }
    }

    /*!
     Finds the option which has the correct ID , if found it returns an option structure.

     \param $optionString must contain the multioption ID an underscore (_) and a the option ID.
    */
    function productOptionInformation( $objectAttribute, $optionID, $productItem )
    {
        //echo "productOptionInformation-";
        $multioption = $objectAttribute->attribute( 'content' );

        foreach ( $multioption->attribute( 'multioption_list' ) as $multioptionElement )
        {
            foreach ( $multioptionElement['optionlist'] as $option )
            {
                if ( $option['option_id'] != $optionID )
                    continue;

                return array( 'id' => $option['option_id'],
                              'name' => $multioptionElement['name'],
                              'value' => $option['value']/*,
                              'additional_price' => $option['additional_price']*/ );
            }
        }
    }

    function title( $contentObjectAttribute, $name = "name" )
    {
        //echo "title-";
        $multioption = $contentObjectAttribute->content();
        return $multioption->attribute( $name );
    }

    /*!
      \return \c true if there are more than one multioption in the list.
    */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        //echo "hasObjectAttributeContent-";
        $multioption = $contentObjectAttribute->content();
        $multioptions = $multioption->attribute( 'multioption_list' );
        return count( $multioptions ) > 0;
    }

    /*!
     Sets default multioption values.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        //echo "initializeObjectAttribute-";
        if ( $currentVersion == false )
        {
            $multioption = $contentObjectAttribute->content();
            if ( $multioption )
            {
                $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
                $multioption->setName( $contentClassAttribute->attribute( 'data_text1' ) );
                $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );
                $contentObjectAttribute->setContent( $multioption );
            }
        }
        else
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        //echo "fetchClassAttributeHTTPInput-";
        $classAttributeID = $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( 'CustomActionButton' ) )
        {
            $actions = $http->postVariable('CustomActionButton');
            if (!empty($actions))
            {
                foreach($actions as $action=>$action_string)
                {
                    switch($action)
                    {
                        case $classAttributeID . '_new_multioption':
                            $xmlString = $classAttribute->attribute('data_text5');
                            $multioption = new NXCMultiOptions( );

                            if (!empty($xmlString)) $multioption->decodeXML($xmlString);

                            $newID = $multioption->addMultiOption( "", 0 );
                            $multioption->addOption( $newID, "", "" );

                            $classAttribute->setAttribute( 'data_text5', $multioption->xmlString() );
                            return true;
                        break;
                        
                        case $classAttributeID . '_remove_selected_multioption':
                            if ($http->hasPostVariable($base.'_data_multioption_remove_'.$classAttributeID))
                            {
                                $indexes = $http->postVariable($base.'_data_multioption_remove_'.$classAttributeID);
                                if (!empty($indexes) and is_array($indexes))
                                {
                                    $xmlString = $classAttribute->attribute('data_text5');
                                    if (!empty($xmlString)) 
                                    {
                                        $multioption = new NXCMultiOptions( );
                                        $multioption->decodeXML($xmlString);
                                        $multioption->removeMultiOptions($indexes, true);
                                        $classAttribute->setAttribute( 'data_text5', $multioption->xmlString() );
                                    }
                                }
                            }
                            return true;
                        break;
                        
                        default:
                            list($classAttributeID, $actionName, $multiOptionId) = explode('_',$action);
                            switch($actionName)
                            {
                                case 'new-option':
                                    $xmlString = $classAttribute->attribute('data_text5');
                                    if (!empty($xmlString)) 
                                    {
                                        $multioption = new NXCMultiOptions( );
                                        $multioption->decodeXML($xmlString);
                                        $multioption->addOption($multiOptionId, "", "");
                                        $classAttribute->setAttribute( 'data_text5', $multioption->xmlString() );
                                    }
                                    return true;
                                break;
                                
                                case 'remove-selected-option':
                                    $postVarName = "{$base}_data_option_remove_{$classAttributeID}_{$multiOptionId}";
                                    if ($http->hasPostVariable($postVarName))
                                    {
                                        $indexes = $http->postVariable($postVarName);
                                        if (!empty($indexes) and is_array($indexes))
                                        {
                                            $xmlString = $classAttribute->attribute('data_text5');
                                            if (!empty($xmlString)) 
                                            {
                                                $multioption = new NXCMultiOptions( );
                                                $multioption->decodeXML($xmlString);
                                                $multioption->removeOptions($indexes, $multiOptionId, true);
                                                $classAttribute->setAttribute( 'data_text5', $multioption->xmlString() );
                                            }
                                        }
                                    }
                                    return true;
                                break;
                            }
                        break;
                    }
                }
                
            }
        }
        if ($http->hasPostVariable('StoreButton') && $http->postVariable('StoreButton') == 'OK')
        {
            $xmlString = $classAttribute->attribute('data_text5');
            $multioption = new NXCMultiOptions( );
            if (!empty($xmlString)) $multioption->decodeXML($xmlString);
            
            if ( $http->hasPostVariable( $base . "_data_multioption_id_" . $classAttributeID ) )
            {
                $multioptionIDArray = $http->postVariable( $base . "_data_multioption_id_" . $classAttributeID );

                foreach ( $multioptionIDArray as $id )
                {
                    $multioptionName = trim($http->postVariable( $base . "_data_multioption_name_" . $classAttributeID . '_' . $id ));
                    $optionIDArray = $http->hasPostVariable( $base . "_data_option_id_" . $classAttributeID . '_' . $id )
                                     ? $http->postVariable( $base . "_data_option_id_" . $classAttributeID . '_' . $id )
                                     : array();
                    /*$optionCountArray = $http->hasPostVariable( $base . "_data_option_option_id_" . $classAttributeID . '_' . $id )
                                        ? $http->postVariable( $base . "_data_option_option_id_" . $classAttributeID . '_' . $id )
                                        : array();*/
                    $optionValueArray = $http->hasPostVariable( $base . "_data_option_value_" . $classAttributeID . '_' . $id )
                                        ? $http->postVariable( $base . "_data_option_value_" . $classAttributeID . '_' . $id )
                                        : array();

                    if (empty($multioptionName))
                    {
                        $multioption->removeMultiOption($id);
                        continue;
                    }
                    $multioption->changeMultiOption($id, $multioptionName);

                    for ( $i = 0; $i < count( $optionIDArray ); $i++ )
                    {
                        if ( trim( $optionValueArray[$i] ) != "" )
                        {
                            $multioption->changeOption($id, $optionIDArray[$i],$optionValueArray[$i]);
                        }
                    }
                }
                $classAttribute->setAttribute('data_text5', $multioption->xmlString());
                $classAttribute->store();
            }            
        }
        /*$defaultValueName = $base . self::DEFAULT_NAME_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $defaultValueName ) )
        {
            $defaultValueValue = $http->postVariable( $defaultValueName );
            //echo "[$defaultValueValue]";
            if ( $defaultValueValue == "" )
            {
                $defaultValueValue = "";
            }
            $classAttribute->setAttribute( 'data_text1', $defaultValueValue );
            return true;
        }*/
        return true;
    }

    /*!
     Returns the content data for the given content class attribute.
    */
    function classAttributeContent( $classAttribute )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $xmlString = $classAttribute->attribute( 'data_text5' );
        $multioptions = array();
        if ( $xmlString != '' )
        {
            $success = $dom->loadXML( $xmlString );
            if ( $success )
            {
                $root = $dom->documentElement;
                // set the name of the node
                //$name = $root->getElementsByTagName( "name" )->item( 0 )->textContent;
                //$optionCounter = $root->getAttribute( "option_counter" );
                $multioptionsNode = $root->getElementsByTagName( "multioptions" )->item( 0 );
                $multioptionsList = $multioptionsNode->getElementsByTagName( "multioption" );
                //Loop for MultiOptions
                foreach ( $multioptionsList as $multioptiondata )
                {
                    $multioption = array();
                    $multioption['id'] = $multioptiondata->getAttribute( "id" );
                    $multioption['name'] = $multioptiondata->getAttribute( "name" );
                    $multioption['priority'] = $multioptiondata->getAttribute( "priority" );
                    //$multioption['default_option_id'] = $multioptiondata->getAttribute( "default_option_id" );
                    $multioption['options'] = array();
                    $optionNode = $multioptiondata->getElementsByTagName( "option" );
                    foreach ( $optionNode as $optiondata )
                    {
                        $option = array();
                        $option['id'] = $optiondata->getAttribute( "id" );
                        $option['option_id'] = $optiondata->getAttribute( "option_id" );
                        $option['value'] = $optiondata->getAttribute( "value" );
                        //$option['additional_price'] = $optiondata->getAttribute( "additional_price" );
                        $multioption['options'][] = $option;
                    }
                    $multioptions[] = $multioption;
                }
            }
        }
        return array( 'multioption_list' => $multioptions );
    }    
    
    function toString( $contentObjectAttribute )
    {
        //echo "toString-";
        $content = $contentObjectAttribute->attribute( 'content' );

        $multioptionArray = array();

        $setName = $content->attribute( 'name' );
        $multioptionArray[] = $setName;

        $multioptionList = $content->attribute( 'multioption_list' );

        foreach ( $multioptionList as $key => $option )
        {
            $optionArray = array();
            $optionArray[] = $option['name'];
            //$optionArray[] = $option['default_option_id'];
            foreach ( $option['optionlist'] as $key => $value )
            {
                $optionArray[] = $value['value'];
                //$optionArray[] = $value['additional_price'];
            }
            $multioptionArray[] = eZStringUtils::implodeStr( $optionArray, '|' );
        }
        return eZStringUtils::implodeStr( $multioptionArray, "&" );
    }


    function fromString( $contentObjectAttribute, $string )
    {
        //echo "fromString-";
        if ( $string == '' )
            return true;

        $multioptionArray = eZStringUtils::explodeStr( $string, '&' );

        $multioption = new NXCMultiOptions( );

        $multioption->OptionCounter = 0;
        $multioption->Options = array();
        $multioption->Name = array_shift( $multioptionArray );
        $priority = 1;
        foreach ( $multioptionArray as $multioptionStr )
        {
            $optionArray = eZStringUtils::explodeStr( $multioptionStr, '|' );


            $newID = $multioption->addMultiOption( array_shift( $optionArray ),
                                            $priority/*,
                                            array_shift( $optionArray )*/ );
            $optionID = 0;
            $count = count( $optionArray );
            for ( $i = 0; $i < $count; $i +=2 )
            {
                $multioption->addOption( $newID, $optionID, array_shift( $optionArray )/*, array_shift( $optionArray )*/ );
                $optionID++;
            }
            $priority++;
        }

        $contentObjectAttribute->setAttribute( "data_text", $multioption->xmlString() );

        return $multioption;

    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        //echo "serializeContentClassAttribute-";
        $defaultValue = $classAttribute->attribute( 'data_text5' );
        $dom = $attributeParametersNode->ownerDocument;
        $defaultValueNode = $dom->createElement( 'default-value' );
        $defaultValueNode->appendChild( $dom->createTextNode( $defaultValue ) );
        $attributeParametersNode->appendChild( $defaultValueNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        //echo "unserializeContentClassAttribute-";
        $defaultValue = $attributeParametersNode->getElementsByTagName( 'default-value' )->item( 0 )->textContent;
        $classAttribute->setAttribute( 'data_text5', $defaultValue );
    }

    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        //echo "serializeContentObjectAttribute-";
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $objectAttribute->attribute( 'data_text' ) );

        $importedRoot = $node->ownerDocument->importNode( $dom->documentElement, true );
        $node->appendChild( $importedRoot );

        return $node;
    }

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        //echo "unserializeContentObjectAttribute-";
        $rootNode = $attributeNode->getElementsByTagName( 'nxcmultioptions' )->item( 0 );
        $xmlString = $rootNode ? $rootNode->ownerDocument->saveXML( $rootNode ) : '';
        $objectAttribute->setAttribute( 'data_text', $xmlString );
    }
}

eZDataType::register( NXCMultiOptionsType::DATA_TYPE_STRING, "NXCMultiOptionsType" );

?>

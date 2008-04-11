<?php
// XML Parsing class for MEMEdex API
// 
// Usage: include this file in your script, and create an object


class bcx_MemedexXMLParser {

    // These variables will store the critical info stored in the XML
    public $api_key;
    public $url;
    public $xmlreturn;

    public $index = array();
    public $values = array();

    public $question = "";
    public $answers = array();
    public $poll_creator = array();
    public $poll_type;
    public $post_url;
    public $poll_id;

    // The parser
    public $xml_parser;

    // a global representing the tag currently open
    public $tag;

    function __construct($api_key) {
	$this->api_key = $api_key;

	$this->url = "http://www.memedex.com/api/getpollxml.php";

	$this->url .= "?api_key={$this->api_key}";
    }

    function getPoll($options) {
	// Append options to URL
	foreach($options AS $key=>$value) {
	    $this->url .= "&{$key}=".urlencode($value);
	}

	// Retrieve XML
       	$this->xmlreturn = implode("",file($this->url));

	// Create an XML parser
        $this->xml_parser = xml_parser_create();

        // Parse the data
	xml_parse_into_struct($this->xml_parser, $this->xmlreturn, &$this->values, &$this->index);

        // Free up memory used by the XML parser
        xml_parser_free($this->xml_parser);

	$this->a_s = print_r($this->index, true);
	$this->b_s = print_r($this->values, true);

	return $this->processXML();
    }

    function getDynamicPoll($options, $question, $answers, $mode="single", $category="8") {
	// Switch to alt url
	$this->url = "http://www.memedex.com/api/createpollxml.php";
        $this->url .= "?api_key={$this->api_key}";

        // Append options to URL
        foreach($options AS $key=>$value) {
            $this->url .= "&{$key}=".urlencode($value);
        }

	// Add the question and answers to the request
	$this->url .= "&question=".urlencode($question);
        $this->url .= "&category=".$category;
        $this->url .= "&poll_mode=".$mode;

	foreach($answers AS $answer) {
            $this->url .= "&answers[]=".urlencode($answer);
	}

        // Retrieve XML
        $this->xmlreturn = implode("",file($this->url));

        // Create an XML parser
        $this->xml_parser = xml_parser_create();

        // Parse the data
        xml_parse_into_struct($this->xml_parser, $this->xmlreturn, &$this->values, &$this->index);

        // Free up memory used by the XML parser
        xml_parser_free($this->xml_parser);

        $this->a_s = print_r($this->index, true);
        $this->b_s = print_r($this->values, true);

        return $this->processXML();
    }

    function processXML() {
	// Get poll question
	$this->question = $this->values[$this->index['QUESTION'][0]]['value'];

	// Get answers
	foreach($this->index['ANSWER'] AS $value) {
	    $answer = $this->values[$value];
	    $this->answers[$answer['attributes']['ORDER']] = array('id'=>$answer['attributes']['ID'],'answer_text'=>$answer['value']);
	}	

	// Get poll type
	$this->poll_type = $this->values[$this->index['POLL_TYPE'][0]]['value'];

	// Get poll id
	$this->poll_id = $this->values[$this->index['POLL_ID'][0]]['value'];

	// Get poll creator info
	$this->poll_creator = array('id'=>$this->values[$this->index['CREATOR_ID'][0]]['value'],'name'=>$this->values[$this->index['CREATOR_STRING'][0]]['value']);	
	$this->poll_creator['avatar_url'] = "http://www.memedex.com/userlogo.php?u={$this->poll_creator['id']}&sz=S";

	// Get post URL
	$this->post_url = $this->values[$this->index['POST_URL'][0]]['value'];

	return true;
    }

}

?>

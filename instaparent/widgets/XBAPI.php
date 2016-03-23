<?php
namespace Kigo\Themes\instaparent;
/**
 * XBAPI is Bapi Extended.
 */
class XBAPI extends \BAPI {

    private $base_url;
    private $api_key;

    /**
     *
     * @throws Exception if api key is not defined
     */
    function __construct() {

        //ApiKey
        $this->api_key = get_option('api_key');

        //if(empty($this->api_key)) throw new Exception('API KEY is empty');
        //Solution data
        $solution_data = json_decode(get_option('bapi_solutiondata'));

        //BaseURL
        $this->base_url = $solution_data->BaseURL;
        parent::__construct($this->api_key, $this->base_url);
    }




    /**
     * This function gets all properties of this solution.
     * @return array   returns an array;
     */
    function getAllProperties($propsToRequest = null) {

        if (empty($propsToRequest)) {
            $propIdStr = $this->xconnect('/ws/?method=search&entity=property');
            $propId = json_decode($propIdStr);

            $maxnum = 20;

            $props = array();
            $propsToRequest = array_chunk($propId->result, $maxnum); //request max elements.
        }

        foreach ($propsToRequest as $requestIds) {
            $ids = implode(',', $requestIds);
            $propsRequested = json_decode($this->xconnect('/ws/?method=get&entity=property&seo=1&ids=' . $ids));
            $props = array_merge($props, $propsRequested->result);
        }

        return $props;
    }


    /**
     *
     * @param string $findIds
     * @return array of objects
     */
    function getPropertyFinders($findIds=[]){

        if(empty($findIds))
            $findIds = json_decode($this->xconnect('/ws/?method=search&entity=searches&seo=1'))->result ? : [];
        else
            $findIds = array_values($findIds);
            
        $pfinders = json_decode($this->xconnect('/ws/?method=get&entity=searches&ids='.implode(',',$findIds).'&seo=1'))->result ? : [];
        return $pfinders;
    }


    function getSpecialOffers($findIds=[])
    {


        if(empty($findIds))
            $findIds = json_decode($this->xconnect('/ws/?method=search&entity=specials&seo=1'))->result ? : [];
        else
            $findIds = array_values($findIds);

        $spoffers = json_decode($this->xconnect('/ws/?method=get&entity=specials&ids='.implode(',',$findIds).'&seo=1'))->result ? : [];

        return $spoffers;
    }



    /**
     * Makes a request to the bapi via webservice.
     * @param $requestString
     * @return string
     */
    private function xconnect($requestString) {

        $args = array(
            'timeout'     => 30,
            'redirection' => 35,
            'httpversion' => '1.0',
            'user-agent'  => 'InstaSites Agent',
        );

        $response = wp_remote_get($this->base_url . $requestString . '&apikey=' . $this->api_key, $args);
        $output = $response['body'];

        return $output;
    }

}
<?php 
/*
Plugin Name: Premium Exchanger hooks
Plugin URI: http://best-curs.info
Description: Actions and filters
Version: 0.1
Author: Best-Curs.info
Author URI: http://best-curs.info
*/

if( !defined( 'ABSPATH')){ exit(); }

add_filter('get_pn_parser','get_pn_parser_bitfinex');
function get_pn_parser_bitfinex($parsers){

    $newparsers = array(
        '700' => array(
            'title' => 'BTC - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '701' => array(
            'title' => 'USD - BTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '702' => array(
            'title' => 'LTC - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '703' => array(
            'title' => 'USD - LTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '704' => array(
            'title' => 'LTC - BTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '705' => array(
            'title' => 'BTC - LTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '706' => array(
            'title' => 'ETH - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '707' => array(
            'title' => 'USD - ETH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '708' => array(
            'title' => 'ETH - BTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '709' => array(
            'title' => 'BTC - ETH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '710' => array(
            'title' => 'ETC - BTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '711' => array(
            'title' => 'BTC - ETC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '712' => array(
            'title' => 'ETC - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '713' => array(
            'title' => 'USD - ETC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '714' => array(
            'title' => 'XMR - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '715' => array(
            'title' => 'USD - XMR',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '716' => array(
            'title' => 'XRP - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '717' => array(
            'title' => 'USD - XRP',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '718' => array(
            'title' => 'BCH - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '719' => array(
            'title' => 'USD - BCH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '720' => array(
            'title' => 'BTG - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '721' => array(
            'title' => 'USD - BTG',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '722' => array(
            'title' => 'ZEC - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '723' => array(
            'title' => 'USD - ZEC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '724' => array(
            'title' => 'DSH - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '725' => array(
            'title' => 'USD - DSH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 100,
        ),
        '726' => array(
            'title' => 'DSH - BTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 100,
        ),
        '727' => array(
            'title' => 'BTC - DSH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '728' => array(
            'title' => 'TRX - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '729' => array(
            'title' => 'USD - TRX',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '730' => array(
            'title' => 'EOS - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '731' => array(
            'title' => 'USD - EOS',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '732' => array(
            'title' => 'IOTA - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '733' => array(
            'title' => 'USD - IOTA',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '734' => array(
            'title' => 'NEO - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '735' => array(
            'title' => 'USD - NEO',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '736' => array(
            'title' => 'OMG - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '737' => array(
            'title' => 'USD - OMG',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '738' => array(
            'title' => 'ZRX - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '739' => array(
            'title' => 'USD - ZRX',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '740' => array(
            'title' => 'QTUM - USD',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '741' => array(
            'title' => 'USD - QTUM',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '742' => array(
            'title' => 'BTC - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '743' => array(
            'title' => 'RUB - BTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '744' => array(
            'title' => 'LTC - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '745' => array(
            'title' => 'RUB - LTC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '746' => array(
            'title' => 'ETH - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '747' => array(
            'title' => 'RUB - ETH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '748' => array(
            'title' => 'ETC - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '749' => array(
            'title' => 'RUB - ETC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '750' => array(
            'title' => 'XMR - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '751' => array(
            'title' => 'RUB - XMR',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '752' => array(
            'title' => 'XRP - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '753' => array(
            'title' => 'RUB - XRP',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '754' => array(
            'title' => 'BCH - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '755' => array(
            'title' => 'RUB - BCH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '756' => array(
            'title' => 'BTG - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '757' => array(
            'title' => 'RUB - BTG',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '758' => array(
            'title' => 'ZEC - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '759' => array(
            'title' => 'RUB - ZEC',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1000,
        ),
        '760' => array(
            'title' => 'DSH - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '761' => array(
            'title' => 'RUB - DSH',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 100,
        ),
        '762' => array(
            'title' => 'TRX - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '763' => array(
            'title' => 'RUB - TRX',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '764' => array(
            'title' => 'EOS - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 100,
        ),
        '765' => array(
            'title' => 'RUB - EOS',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '766' => array(
            'title' => 'IOT - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '767' => array(
            'title' => 'RUB - IOT',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '768' => array(
            'title' => 'NEO - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 100,
        ),
        '769' => array(
            'title' => 'RUB - NEO',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '770' => array(
            'title' => 'OMG - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '771' => array(
            'title' => 'RUB - OMG',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '772' => array(
            'title' => 'ZRX - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '773' => array(
            'title' => 'RUB - ZRX',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '774' => array(
            'title' => 'QTM - RUB',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),
        '775' => array(
            'title' => 'RUB - QTM',
            'birg' => __('Bitfinex','pn'),
            'data' => array('mid','bid','ask','last_price','low','high'),
            'curs' => 1,
        ),

    );
    foreach($newparsers as $key => $data){
        $parsers[$key] = $data;
    }

    return $parsers;
}

add_filter('before_load_curs_parser','before_load_curs_parser_bitfinex', 10, 4);
function before_load_curs_parser_bitfinex($curs_parser, $work_parser='', $config_parser='', $parsers=''){

    if(!is_array($parsers)){
        $parsers = apply_filters('get_pn_parser', array());
    }

    $arrs = array(
        'BTCUSD' => array(
            'id1' => 700,
            'sum1' => 1,
            'id2' => 701,
            'sum2' => 1000,
        ),
        'LTCUSD' => array(
            'id1' => 702,
            'sum1' => 1,
            'id2' => 703,
            'sum2' => 1000,
        ),
        'LTCBTC' => array(
            'id1' => 704,
            'sum1' => 1,
            'id2' => 705,
            'sum2' => 1,
        ),
        'ETHUSD' => array(
            'id1' => 706,
            'sum1' => 1,
            'id2' => 707,
            'sum2' => 1000,
        ),
        'ETHBTC' => array(
            'id1' => 708,
            'sum1' => 1,
            'id2' => 709,
            'sum2' => 1,
        ),
        'ETCBTC' => array(
            'id1' => 710,
            'sum1' => 1000,
            'id2' => 711,
            'sum2' => 1,
        ),
        'ETCUSD' => array(
            'id1' => 712,
            'sum1' => 1,
            'id2' => 713,
            'sum2' => 1000,
        ),
        'XMRUSD' => array(
            'id1' => 714,
            'sum1' => 1,
            'id2' => 715,
            'sum2' => 1000,
        ),
        'XRPUSD' => array(
            'id1' => 716,
            'sum1' => 1,
            'id2' => 717,
            'sum2' => 1,
        ),
        'BCHUSD' => array(
            'id1' => 718,
            'sum1' => 1,
            'id2' => 719,
            'sum2' => 1000,
        ),
        'BTGUSD' => array(
            'id1' => 720,
            'sum1' => 1,
            'id2' => 721,
            'sum2' => 1000,
        ),
        'ZECUSD' => array(
            'id1' => 722,
            'sum1' => 1,
            'id2' => 723,
            'sum2' => 1000,
        ),
        'DSHUSD' => array(
            'id1' => 724,
            'sum1' => 1,
            'id2' => 725,
            'sum2' => 1000,
        ),
        'DSHBTC' => array(
            'id1' => 726,
            'sum1' => 100,
            'id2' => 727,
            'sum2' => 1,
        ),
        'TRXUSD' => array(
            'id1' => 728,
            'sum1' => 100,
            'id2' => 729,
            'sum2' => 1,
        ),
        'EOSUSD' => array(
            'id1' => 730,
            'sum1' => 100,
            'id2' => 731,
            'sum2' => 1,
        ),
        'IOTUSD' => array(
            'id1' => 732,
            'sum1' => 100,
            'id2' => 733,
            'sum2' => 1,
        ),
        'NEOUSD' => array(
            'id1' => 734,
            'sum1' => 100,
            'id2' => 735,
            'sum2' => 1,
        ),
        'OMGUSD' => array(
            'id1' => 736,
            'sum1' => 100,
            'id2' => 737,
            'sum2' => 1,
        ),
        'ZRXUSD' => array(
            'id1' => 738,
            'sum1' => 100,
            'id2' => 739,
            'sum2' => 1,
        ),
        'QTMUSD' => array(
            'id1' => 740,
            'sum1' => 1,
            'id2' => 741,
            'sum2' => 1,
        ),
        'BTCRUB' => array(
            'id1' => 742,
            'sum1' => 1,
            'id2' => 743,
            'sum2' => 1000,
        ),
        'LTCRUB' => array(
            'id1' => 744,
            'sum1' => 1,
            'id2' => 745,
            'sum2' => 1000,
        ),
        'ETHRUB' => array(
            'id1' => 746,
            'sum1' => 1,
            'id2' => 747,
            'sum2' => 1000,
        ),
        'ETCRUB' => array(
            'id1' => 748,
            'sum1' => 1,
            'id2' => 749,
            'sum2' => 1000,
        ),
        'XMRRUB' => array(
            'id1' => 750,
            'sum1' => 1,
            'id2' => 751,
            'sum2' => 1000,
        ),
        'XRPRUB' => array(
            'id1' => 752,
            'sum1' => 1,
            'id2' => 753,
            'sum2' => 1,
        ),
        'BCHRUB' => array(
            'id1' => 754,
            'sum1' => 1,
            'id2' => 755,
            'sum2' => 1000,
        ),
        'BTGRUB' => array(
            'id1' => 756,
            'sum1' => 1,
            'id2' => 757,
            'sum2' => 1000,
        ),
        'ZECRUB' => array(
            'id1' => 758,
            'sum1' => 1,
            'id2' => 759,
            'sum2' => 1000,
        ),
        'DSHRUB' => array(
            'id1' => 760,
            'sum1' => 1,
            'id2' => 761,
            'sum2' => 100,
        ),
        'TRXRUB' => array(
            'id1' => 762,
            'sum1' => 1,
            'id2' => 763,
            'sum2' => 1,
        ),
        'EOSRUB' => array(
            'id1' => 764,
            'sum1' => 100,
            'id2' => 765,
            'sum2' => 1,
        ),
        'IOTRUB' => array(
            'id1' => 766,
            'sum1' => 1,
            'id2' => 767,
            'sum2' => 1,
        ),
        'NEORUB' => array(
            'id1' => 768,
            'sum1' => 100,
            'id2' => 769,
            'sum2' => 1,
        ),
        'OMGRUB' => array(
            'id1' => 770,
            'sum1' => 1,
            'id2' => 771,
            'sum2' => 1,
        ),
        'ZRXRUB' => array(
            'id1' => 772,
            'sum1' => 1,
            'id2' => 773,
            'sum2' => 1,
        ),
        'QTMRUB' => array(
            'id1' => 774,
            'sum1' => 1,
            'id2' => 775,
            'sum2' => 1,
        ),
    );

    $curl = get_curl_parser('https://api.bitfinex.com/v1/tickers?symbols', '', 'parser', 'bitfinex');
    if(!$curl['err']){
        $outs = @json_decode($curl['output']);
        print_r($outs);
        if(is_array($outs)){
            foreach($arrs as $arr_id => $arr_data){
                foreach($outs as $item){
                    if(isset($item->pair) and $item->pair == $arr_id){
                        $id1 = intval(is_isset($arr_data, 'id1'));
                        $sum1 = intval(is_isset($arr_data, 'sum1'));
                        $id2 = intval(is_isset($arr_data, 'id2'));
                        $sum2 = intval(is_isset($arr_data, 'sum2'));

                        if(is_isset($work_parser, $id1) == 1){
                            $key1 = trim(is_isset($config_parser, $id1));
                            if(!$key1){ $key1 = 'mid'; }
                            $ck1 = is_my_money($item->$key1);
                            $curs1 = def_parser_curs($parsers, $id1, $sum1);
                            if($ck1){
                                $curs_parser[$id1]['curs1'] = $curs1;
                                $curs_parser[$id1]['curs2'] = $ck1 * $curs1;
                            }
                        }
                        if(is_isset($work_parser, $id2) == 1){
                            $key2 = trim(is_isset($config_parser, $id2));
                            if(!$key2){ $key2 = 'mid'; }
                            $ck2def = is_my_money($item->$key2);
                            $curs2 = def_parser_curs($parsers, $id2, $sum2);
                            if($curs2 and $ck2def){
                                $ck2 = is_my_money($curs2 / $ck2def);
                                $curs_parser[$id2]['curs1'] = $curs2;
                                $curs_parser[$id2]['curs2'] = $ck2;
                            }
                        }
                    }
                }
            }
        }
    }

    print_r($curs_parser);
    /*print_r($curs_parser[2]);
    print_r($curs_parser[700]);
    print_r($curs_parser[701]);*/

    //BTCRUB
    $curs_parser[742]['curs1'] = 1; // USD
    $curs_parser[742]['curs2'] = $curs_parser[700]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[743]['curs1'] = 1000; // RUB
    $curs_parser[743]['curs2'] = ($curs_parser[701]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //LTCRUB
    $curs_parser[744]['curs1'] = 1; // USD
    $curs_parser[744]['curs2'] = $curs_parser[702]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[745]['curs1'] = 1000; // RUB
    $curs_parser[745]['curs2'] = ($curs_parser[703]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //ETHRUB
    $curs_parser[746]['curs1'] = 1; // USD
    $curs_parser[746]['curs2'] = $curs_parser[706]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[747]['curs1'] = 1000; // RUB
    $curs_parser[747]['curs2'] = ($curs_parser[707]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //ETCRUB
    $curs_parser[748]['curs1'] = 1; // USD
    $curs_parser[748]['curs2'] = $curs_parser[712]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[749]['curs1'] = 1000; // RUB
    $curs_parser[749]['curs2'] = ($curs_parser[713]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //XMRRUB
    $curs_parser[750]['curs1'] = 1; // USD
    $curs_parser[750]['curs2'] = $curs_parser[714]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[751]['curs1'] = 1000; // RUB
    $curs_parser[751]['curs2'] = ($curs_parser[715]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //XRPRUB
    $curs_parser[752]['curs1'] = 1; // USD
    $curs_parser[752]['curs2'] = $curs_parser[716]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[753]['curs1'] = 1; // RUB
    $curs_parser[753]['curs2'] = ($curs_parser[717]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //BCHRUB
    $curs_parser[754]['curs1'] = 1; // USD
    $curs_parser[754]['curs2'] = $curs_parser[718]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[755]['curs1'] = 1000; // RUB
    $curs_parser[755]['curs2'] = ($curs_parser[719]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //BTGRUB
    $curs_parser[756]['curs1'] = 1; // USD
    $curs_parser[756]['curs2'] = $curs_parser[720]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[757]['curs1'] = 1000; // RUB
    $curs_parser[757]['curs2'] = ($curs_parser[721]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //ZECRUB
    $curs_parser[758]['curs1'] = 1; // USD
    $curs_parser[758]['curs2'] = $curs_parser[722]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[759]['curs1'] = 1000; // RUB
    $curs_parser[759]['curs2'] = ($curs_parser[723]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //DSHRUB
    $curs_parser[760]['curs1'] = 1; // USD
    $curs_parser[760]['curs2'] = $curs_parser[724]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[761]['curs1'] = 100; // RUB
    $curs_parser[761]['curs2'] = ($curs_parser[725]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //TRXRUB
    $curs_parser[762]['curs1'] = 1; // USD
    $curs_parser[762]['curs2'] = $curs_parser[728]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[763]['curs1'] = 1; // RUB
    $curs_parser[763]['curs2'] = ($curs_parser[729]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //EOSRUB
    $curs_parser[764]['curs1'] = 100; // USD
    $curs_parser[764]['curs2'] = $curs_parser[730]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[765]['curs1'] = 1; // RUB
    $curs_parser[765]['curs2'] = ($curs_parser[731]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //IOTRUB
    $curs_parser[766]['curs1'] = 1; // USD
    $curs_parser[766]['curs2'] = $curs_parser[732]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[767]['curs1'] = 1; // RUB
    $curs_parser[767]['curs2'] = ($curs_parser[733]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //NEORUB
    $curs_parser[768]['curs1'] = 100; // USD
    $curs_parser[768]['curs2'] = $curs_parser[734]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[769]['curs1'] = 1; // RUB
    $curs_parser[769]['curs2'] = ($curs_parser[735]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //OMGRUB
    $curs_parser[770]['curs1'] = 1; // USD
    $curs_parser[770]['curs2'] = $curs_parser[736]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[771]['curs1'] = 1; // RUB
    $curs_parser[771]['curs2'] = ($curs_parser[737]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //ZRXRUB
    $curs_parser[772]['curs1'] = 1; // USD
    $curs_parser[772]['curs2'] = $curs_parser[738]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[773]['curs1'] = 1; // RUB
    $curs_parser[773]['curs2'] = ($curs_parser[739]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB

    //QTMRUB
    $curs_parser[774]['curs1'] = 1; // USD
    $curs_parser[774]['curs2'] = $curs_parser[740]['curs2'] * $curs_parser[1]['curs2']; // USD
    $curs_parser[775]['curs1'] = 1; // RUB
    $curs_parser[775]['curs2'] = ($curs_parser[741]['curs2'] * $curs_parser[2]['curs2']) / 1000.0; // RUB


    return $curs_parser;
}
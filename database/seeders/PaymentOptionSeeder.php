<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PaymentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        \DB::table('payment_options')->delete();
 
      $opt = array(
        array('id' => '1','code' => 'COD','path' => '','title' => 'Cash On Delivery','status' => '1'),
        array('id' => '2','code' => 'wallet','path' => '','title' => 'Wallet','status' => '1'),
        array('id' => '3','code' => 'layalty-points','path' => '','title' => 'Layalty Points','status' => '1'),
        array('id' => '8', 'path' => 'omnipay/tpay', 'code' => 'tpay', 'title' => 'Tpay', 'status' => '0'),
        array('id' => '9','path' =>'dilab/omnipay-2c2p', 'code' => '2c2p', 'title' => '2c2p', 'status' => '0'),
        array('id' => '10','path' => 'collizo4sky/omnipay-2checkout', 'code' => '2checkout', 'title' => '2Checkout', 'status' => '0'),
        array('id' => '11','path' => 'agmscode/omnipay-agms', 'code' => 'agms', 'title' => 'AGMS', 'status' => '0'),
        array('id' => '12','path' => 'lokielse/omnipay-global-alipay', 'code' => 'global-alipay', 'title' => 'Alipay (Global)', 'status' => '0'),

        array('id' => '13', 'path' => 'lokielse/omnipay-alipay', 'code' => 'alipay', 'title' => 'Alipay', 'status' => '0'),
        array('id' => '14', 'path' => 'x-class/omnipay-99bill', 'code' => '99bill', 'title' => '99Bill', 'status' => '0'),
        array('id' => '15', 'path' => 'delatbabel/omnipay-alliedwallet', 'code' => 'alliedwallet', 'title' => 'Allied Wallet', 'status' => '0'),
        array('id' => '16', 'path' => 'omnipay/authorizenet', 'code' => 'authorizenet', 'title' => 'Authorize.Net', 'status' => '0'),
        array('id' => '17', 'path' => 'academe/omnipay-authorizenetapi', 'code' => 'authorizenetapi', 'title' => 'Authorize.Net API', 'status' => '0'),
        array('id' => '18', 'path' => 'digitickets/omnipay-barclays-epdq', 'code' => 'barclays-epdq', 'title' => 'Barclays ePDQ', 'status' => '0'),
        array('id' => '19', 'path' => 'lemonstand/omnipay-beanstream', 'code' => 'beanstream', 'title' => 'Beanstream', 'status' => '0'),
        array('id' => '20', 'path' => 'yasinkuyu/omnipay-bkm', 'code' => 'bkm', 'title' => 'BKM Express', 'status' => '0'),
        array('id' => '21', 'path' => 'vimeo/omnipay-bluesnap', 'code' => 'bluesnap', 'title' => 'BlueSnap', 'status' => '0'),
        array('id' => '22', 'path' => 'omnipay/braintree', 'code' => 'braintree', 'title' => 'Braintree', 'status' => '0'),
        array('id' => '23', 'path' => 'omnipay/buckaroo', 'code' => 'buckaroo', 'title' => 'Buckaroo', 'status' => '0'),
        array('id' => '24', 'path' => 'cardgate/omnipay-cardgate', 'code' => 'cardgate', 'title' => 'CardGate', 'status' => '0'),
        array('id' => '25', 'path' => 'omnipay/cardsave', 'code' => 'cardsave', 'title' => 'CardSave', 'status' => '0'),
        array('id' => '26', 'path' => 'omnipay/cashbaba', 'code' => 'cashbaba', 'title' => 'CashBaBa', 'status' => '0'),
        array('id' => '27', 'path' => 'fotografde/checkoutcom', 'code' => 'checkoutcom', 'title' => 'Checkout.com', 'status' => '0'),
        array('id' => '28', 'path' => 'cloudbanking/omnipay-cloudbanking', 'code' => 'cloudbanking', 'title' => 'CloudBanking', 'status' => '0'),
        array('id' => '29', 'path' => 'omnipay/coinbase', 'code' => 'coinbase', 'title' => 'Coinbase', 'status' => '0'),
        array('id' => '30', 'path' => 'coingate/omnipay-coingate', 'code' => 'coingate', 'title' => 'CoinGate', 'status' => '0'),
        array('id' => '31', 'path' => 'meebio/omnipay-creditcall', 'code' => 'creditcall', 'title' => 'Creditcall', 'status' => '0'),
        array('id' => '32', 'path' => 'dioscouri/omnipay-cybersource', 'code' => 'cybersource', 'title' => 'Cybersource', 'status' => '0'),
        array('id' => '33', 'path' => 'dabsquared/omnipay-cybersource-soap', 'code' => 'cybersource-soap', 'title' => 'Cybersource SOAP', 'status' => '0'),
        array('id' => '34', 'path' => 'digitickets/omnipay-datacash', 'code' => 'datacash', 'title' => 'DataCash', 'status' => '0'),
        array('id' => '35', 'path' => 'w-vision/datatrans', 'code' => 'datatrans', 'title' => 'Datatrans', 'status' => '0'),
        array('id' => '36', 'path' => 'academe/omnipay-datatrans', 'code' => 'datatrans', 'title' => 'Datatrans', 'status' => '0'),
        array('id' => '37', 'path' => 'uskur/omnipay-docdata-payments', 'code' => 'docdata-payments', 'title' => 'Docdata Payments', 'status' => '0'),
        array('id' => '38', 'path' => 'omnipay/dummy', 'code' => 'dummy', 'title' => 'Dummy', 'status' => '0'),
        array('id' => '39', 'path' => 'dilab/omnipay-eghl', 'code' => 'eghl', 'title' => 'eGHL', 'status' => '0'),
        array('id' => '40', 'path' => 'dercoder/omnipay-ecopayz', 'code' => 'ecopayz', 'title' => 'ecoPayz', 'status' => '0'),
        array('id' => '41', 'path' => 'pinguinjkeke/omnipay-egopaymentru', 'code' => 'egopaymentru', 'title' => 'EgopayRu', 'status' => '0'),
        array('id' => '42', 'path' => 'lemonstand/omnipay-elavon', 'code' => 'elavon', 'title' => 'Elavon', 'status' => '0'),
        array('id' => '43', 'path' => 'omnipay/eway', 'code' => 'eway', 'title' => 'eWAY', 'status' => '0'),
        array('id' => '44', 'path' => 'andreas22/omnipay-fasapay', 'code' => 'fasapay', 'title' => 'Fasapay', 'status' => '0'),
        array('id' => '45', 'path' => 'delatbabel/omnipay-fatzebra', 'code' => 'fatzebra', 'title' => 'Fat Zebra', 'status' => '0'),
        array('id' => '46', 'path' => 'omnipay/firstdata', 'code' => 'firstdata', 'title' => 'First Data', 'status' => '0'),
        array('id' => '47', 'path' => 'colinodell/omnipay-zero', 'code' => 'zero', 'title' => 'Free / Zero Amount', 'status' => '0'),
        array('id' => '48', 'path' => 'guisea/omnipay-flo2cash', 'code' => 'flo2cash', 'title' => 'Flo2cash', 'status' => '0'),
        array('id' => '49', 'path' => 'academe/omnipay-girocheckout', 'code' => 'girocheckout', 'title' => 'GiroCheckout', 'status' => '0'),
        array('id' => '50', 'path' => 'dercoder/omnipay-globalcloudpay', 'code' => 'globalcloudpay', 'title' => 'Globalcloudpay', 'status' => '0'),
        array('id' => '51', 'path' => 'omnipay/gocardless', 'code' => 'gocardless', 'title' => 'GoCardless', 'status' => '0'),
        array('id' => '52', 'path' => 'omnipay/omnipay-govpaynet', 'code' => 'govpaynet', 'title' => 'GovPayNet', 'status' => '0'),
        array('id' => '53', 'path' => 'yasinkuyu/omnipay-gvp', 'code' => 'gvp', 'title' => 'GVP (Garanti)', 'status' => '0'),
        array('id' => '54', 'path' => 'academe/omnipay-helcim', 'code' => 'helcim', 'title' => 'Helcim', 'status' => '0'),
        array('id' => '55', 'path' => 'ptuchik/omnipay-idram', 'code' => 'idram', 'title' => 'iDram', 'status' => '0'),
        array('id' => '56', 'path' => 'dilab/omnipay-ipay88', 'code' => 'ipay88', 'title' => 'iPay88', 'status' => '0'),
        array('id' => '57', 'path' => 'ifthenpay/omnipay-ifthenpay', 'code' => 'ifthenpay', 'title' => 'IfthenPay', 'status' => '0'),
        array('id' => '58', 'path' => 'yasinkuyu/omnipay-iyzico', 'code' => 'iyzico', 'title' => 'Iyzico', 'status' => '0'),
        array('id' => '59', 'path' => 'transportersio/omnipay-judopay', 'code' => 'judopay', 'title' => 'Judo Pay', 'status' => '0'),
        array('id' => '60', 'path' => 'myonlinestore/omnipay-klarna-checkout', 'code' => 'klarna-checkout', 'title' => 'Klarna Checkout', 'status' => '0'),
        array('id' => '61', 'path' => 'byjg/omnipay-komerci', 'code' => 'komerci', 'title' => 'Komerci (Rede, former RedeCard)', 'status' => '0'),
        array('id' => '62', 'path' => 'vink/omnipay-komoju', 'code' => 'komoju', 'title' => 'Komoju', 'status' => '0'),
        array('id' => '63', 'path' => 'dilab/omnipay-midtrans', 'code' => 'midtrans', 'title' => 'Midtrans', 'status' => '0'),
        array('id' => '64', 'path' => 'fruitcake/omnipay-magnius', 'code' => 'magnius', 'title' => 'Magnius', 'status' => '0'),
        array('id' => '65', 'path' => 'omnipay/manual', 'code' => 'manual', 'title' => 'Manual', 'status' => '0'),
        array('id' => '66', 'path' => 'omnipay/migs', 'code' => 'migs', 'title' => 'Migs', 'status' => '0'),
        array('id' => '67', 'path' => 'omnipay/mollie', 'code' => 'mollie', 'title' => 'Mollie', 'status' => '0'),
        array('id' => '68', 'path' => 'leesiongchan/molpay', 'code' => 'molpay', 'title' => 'MOLPay', 'status' => '0'),
        array('id' => '69', 'path' => 'incube8/omnipay-multicards', 'code' => 'multicards', 'title' => 'MultiCards', 'status' => '0'),
        array('id' => '70', 'path' => 'omnipay/multisafepay', 'code' => 'multisafepay', 'title' => 'MultiSafepay', 'status' => '0'),
        array('id' => '71', 'path' => 'xxtime/omnipay-mycard', 'code' => 'mycard', 'title' => 'MyCard', 'status' => '0'),
        array('id' => '72', 'path' => 'sudiptpa/omnipay-nabtransact', 'code' => 'nabtransact', 'title' => 'National Australia Bank (NAB) Transact', 'status' => '0'),
        array('id' => '73', 'path' => 'yasinkuyu/omnipay-nestpay', 'code' => 'nestpay', 'title' => 'NestPay (EST)', 'status' => '0'),
        array('id' => '74', 'path' => 'omnipay/netaxept', 'code' => 'netaxept', 'title' => 'Netaxept (BBS)', 'status' => '0'),
        array('id' => '75', 'path' => 'omnipay/netbanx', 'code' => 'netbanx', 'title' => 'Netbanx', 'status' => '0'),
        array('id' => '76', 'path' => 'dercoder/omnipay-neteller', 'code' => 'neteller', 'title' => 'Neteller', 'status' => '0'),
        array('id' => '77', 'path' => 'netpay/omnipay-netpay', 'code' => 'netpay', 'title' => 'NetPay', 'status' => '0'),
        array('id' => '78', 'path' => 'mfauveau/omnipay-nmi', 'code' => 'nmi', 'title' => 'Network Merchants Inc. (NMI)', 'status' => '0'),
        array('id' => '79', 'path' => 'nocksapp/omnipay-nocks', 'code' => 'nocks', 'title' => 'Nocks', 'status' => '0'),
        array('id' => '80', 'path' => 'dilab/omnipay-onepay', 'code' => 'onepay', 'title' => 'OnePay', 'status' => '0'),
        array('id' => '81', 'path' => 'vdbelt/omnipay-oppwa', 'code' => 'oppwa', 'title' => 'Oppwa', 'status' => '0'),
        array('id' => '82', 'path' => 'dilab/omnipay-payoo', 'code' => 'payoo', 'title' => 'Payoo', 'status' => '0'),
        array('id' => '83', 'path' => 'mfauveau/omnipay-pacnet', 'code' => 'pacnet', 'title' => 'Pacnet', 'status' => '0'),
        array('id' => '84', 'path' => 'descubraomundo/omnipay-pagarme', 'code' => 'pagarme', 'title' => 'Pagar.me', 'status' => '0'),
        array('id' => '85', 'path' => 'yasinkuyu/omnipay-paratika', 'code' => 'paratika', 'title' => 'Paratika (Asseco)', 'status' => '0'),
        array('id' => '86', 'path' => 'omnipay/payfast', 'code' => 'payfast', 'title' => 'PayFast', 'status' => '0'),
        array('id' => '87', 'path' => 'omnipay/payflow', 'code' => 'payflow', 'title' => 'Payflow', 'status' => '0'),
        array('id' => '88', 'path' => 'omnipay/paymentexpress', 'code' => 'paymentexpress', 'title' => 'PaymentExpress (DPS)', 'status' => '0'),
        array('id' => '89', 'path' => 'onlinesid/omnipay-paymentexpress-a2a', 'code' => 'paymentexpress-a2a', 'title' => 'PaymentExpress / DPS (A2A)', 'status' => '0'),
        array('id' => '90', 'path' => 'pinguinjkeke/omnipay-paymentgateru', 'code' => 'paymentgateru', 'title' => 'PaymentgateRu', 'status' => '0'),
        array('id' => '91', 'path' => 'digitickets/omnipay-paymentsense', 'code' => 'paymentsense', 'title' => 'PaymentSense', 'status' => '0'),
        array('id' => '92', 'path' => 'incube8/omnipay-paymentwall', 'code' => 'paymentwall', 'title' => 'PaymentWall', 'status' => '0'),
        array('id' => '93', 'path' => 'omnipay/paypal', 'code' => 'paypal', 'title' => 'PayPal', 'status' => '0'),
        array('id' => '94', 'path' => 'paypronl/omnipay-paypro', 'code' => 'paypro', 'title' => 'PayPro', 'status' => '0'),
        array('id' => '95', 'path' => 'academe/omnipay-payone', 'code' => 'payone', 'title' => 'PAYONE', 'status' => '0'),
        array('id' => '96',  'path' => 'dercoder/omnipay-paysafecard', 'code' => 'paysafecard', 'title' => 'Paysafecard', 'status' => '0'),
        array('id' => '97', 'path' => 'povils/omnipay-paysera', 'code' => 'paysera', 'title' => 'Paysera', 'status' => '0'),
        array('id' => '98', 'path' => 'dranes/omnipay-paysimple', 'code' => 'paysimple', 'title' => 'PaySimple', 'status' => '0'),
        array('id' => '99', 'path' => 'inkedcurtis/omnipay-payssion', 'code' => 'payssion', 'title' => 'PaySsion', 'status' => '0'),
        array('id' => '100', 'path' => 'softcommerce/omnipay-paytrace', 'code' => 'paytrace', 'title' => 'PayTrace', 'status' => '0'),
        array('id' => '101', 'path' => 'omnipay/payu', 'code' => 'payu', 'title' => 'PayU', 'status' => '0'),
        array('id' => '102', 'path' => 'uskur/omnipay-pelecard', 'code' => 'pelecard', 'title' => 'Pelecard', 'status' => '0'),
        array('id' => '103', 'path' => 'omnipay/pin', 'code' => 'pin', 'title' => 'Pin Payments', 'status' => '0'),
        array('id' => '104', 'path' => 'phoenixg/omnipay-pingpp', 'code' => 'pingpp', 'title' => 'Ping++', 'status' => '0'),
        array('id' => '105', 'path' => 'burnbright/omnipay-poli', 'code' => 'poli', 'title' => 'POLi', 'status' => '0'),
        array('id' => '106', 'path' => 'dercoder/omnipay-portmanat', 'code' => 'portmanat', 'title' => 'Portmanat', 'status' => '0'),
        array('id' => '107', 'path' => 'yasinkuyu/omnipay-posnet', 'code' => 'posnet', 'title' => 'Posnet', 'status' => '0'),
        array('id' => '108', 'path' => 'bummzack/omnipay-postfinance', 'code' => 'postfinance', 'title' => 'Postfinance', 'status' => '0'),
        array('id' => '109', 'path' => 'nobrainerweb/omnipay-quickpay', 'code' => 'quickpay', 'title' => 'Quickpay', 'status' => '0'),
        array('id' => '110', 'path' => 'digitickets/omnipay-realex', 'code' => 'realex', 'title' => 'Realex', 'status' => '0'),
        array('id' => '111', 'path' => 'nazka/sermepa-omnipay',  'code' => 'sermepa', 'title' => 'RedSys', 'status' => '0'),
        array('id' => '112', 'path' => 'rentmoola/omnipay-rentmoola', 'code' => 'rentmoola', 'title' => 'RentMoola', 'status' => '0'),
        array('id' => '113', 'path' => 'omnipay/sagepay', 'code' => 'sagepay', 'title' => 'Sage Pay', 'status' => '0'),
        array('id' => '114', 'path' => 'andrewnovikof/omnipay-sberbank' , 'code' => 'sberbank', 'title' => 'Sberbank', 'status' => '0'),
        array('id' => '115', 'path' => 'justinbusschau/omnipay-secpay' , 'code' => 'secpay', 'title' => 'SecPay', 'status' => '0'),
        array('id' => '116', 'path' => 'omnipay/securepay' , 'code' => 'securepay', 'title' => 'SecurePay', 'status' => '0'),
        array('id' => '117', 'path' => 'meebio/omnipay-secure-trading' , 'code' => 'secure-trading', 'title' => 'Secure Trading', 'status' => '0'),
        array('id' => '118', 'path' => 'fruitcakestudio/omnipay-sisow' , 'code' => 'sisow', 'title' => 'Sisow', 'status' => '0'),
        array('id' => '119', 'path' => 'alfaproject/omnipay-skrill' , 'code' => 'skrill', 'title' => 'Skrill', 'status' => '0'),
        array('id' => '120', 'path' => 'aimeoscom/omnipay-sofort', 'code' => 'sofort', 'title' => 'Sofort', 'status' => '0'),
        array('id' => '121', 'path' => 'gregoriohc/omnipay-spreedly' , 'code' => 'spreedly', 'title' => 'Spreedly', 'status' => '0'),
        array('id' => '122', 'path' => 'transportersio/omnipay-square' , 'code' => 'square', 'title' => 'Square', 'status' => '0'),
        array('id' => '123', 'path' => 'omnipay/targetpay', 'code' => 'stripe', 'title' => 'Stripe', 'status' => '0'),
        array('id' => '124', 'path' => 'lokielse/omnipay-unionpay', 'code' => 'targetpay', 'title' => 'TargetPay', 'status' => '0'),
        array('id' => '125', 'path' => 'lemonstand/omnipay-vantiv', 'code' => 'unionpay', 'title' => 'UnionPay', 'status' => '0'),
        array('id' => '126', 'path' => 'andylibrian/omnipay-veritrans', 'code' => 'vantiv', 'title' => 'Vantiv', 'status' => '0'),
        array('id' => '127', 'path' => 'vimeo/omnipay-vindicia', 'code' => 'veritrans', 'title' => 'Veritrans', 'status' => '0'),
        array('id' => '128', 'path' => 'delatbabel/omnipay-vivapayments', 'code' => 'vindicia', 'title' => 'Vindicia', 'status' => '0'),
        array('id' => '129', 'path' => 'dercoder/omnipay-webmoney', 'code' => 'vivapayments', 'title' => 'VivaPayments', 'status' => '0'),
        array('id' => '130', 'path' => 'labs7in0/omnipay-wechat', 'code' => 'webmoney', 'title' => 'WebMoney', 'status' => '0'),
        array('id' => '131', 'path' => 'lokielse/omnipay-wechatpay', 'code' => 'wechat', 'title' => 'WeChat', 'status' => '0'),
        array('id' => '132', 'path' => 'collizo4sky/omnipay-wepay', 'code' => 'wechatpay', 'title' => 'WechatPay', 'status' => '0'),
        array('id' => '133', 'path' => 'igaponov/omnipay-wirecard', 'code' => 'wepay', 'title' => 'WePay', 'status' => '0'),
        array('id' => '134', 'path' => 'academe/omnipay-wirecard', 'code' => 'wirecard', 'title' => 'Wirecard', 'status' => '0'),
        array('id' => '135', 'path' => 'teaandcode/omnipay-worldpay-xml', 'code' => 'wirecard', 'title' => 'Wirecard', 'status' => '0'),
        array('id' => '136', 'path' => 'comicrelief/omnipay-worldpay-cg-hosted', 'code' => 'worldpay-xml', 'title' => 'Worldpay XML Direct Corporate Gateway', 'status' => '0'),
        array('id' => '137', 'path' => 'omnipay/worldpay', 'code' => 'worldpay-cg-hosted', 'title' => 'Worldpay XML Hosted Corporate Gateway', 'status' => '0'),
        array('id' => '138', 'path' => 'yandexmoney/omnipay', 'code' => 'worldpay', 'title' => 'Worldpay Business Gateway', 'status' => '0'),
        ); 
        \DB::table('payment_options')->insert($opt);
    }
}
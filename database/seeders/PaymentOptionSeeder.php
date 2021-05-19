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
        $opt = array(
            array(
                'code' => 'COD',
                'title' => 'Cash On Delivery',
                'status' => '1'
            ),
            array(
                'code' => 'wallet',
                'title' => 'Wallet',
                'status' => '1'
            ),
            array(
                'code' => 'layalty-points',
                'title' => 'Layalty Points',
                'status' => '1'
            ),
            array(
                'code' => '2c2p',
                'title' => '2c2p',
                'status' => '1'
            ),
            array(
                'code' => '2checkout',
                'title' => '2Checkout',
                'status' => '1'
            ),
            array(
                'code' => 'agms',
                'title' => 'AGMS',
                'status' => '1'
            ),
            array(
                'code' => 'global-alipay',
                'title' => 'Alipay(Global)',
                'status' => '1'
            ),
            array(
                'code' => 'alipay',
                'title' => 'Alipay',
                'status' => '1'
            ),
            array(
                'code' => 'alipay',
                'title' => 'Alipay',
                'status' => '1'
            ),
            array(
                'code' => 'alipay',
                'title' => 'Alipay',
                'status' => '1'
            ),
            array(
                'code' => '99bill',
                'title' => '99Bill',
                'status' => '1'
            ),
            array(
                'code' => 'alliedwallet',
                'title' => 'Allied Wallet',
                'status' => '1'
            ),
            array(
                'code' => 'Authorize.Net',
                'title' => 'authorizenet',
                'status' => '1'
            ),

            array(
                'code' => 'Authorize.Net API',
                'title' => 'authorizenetapi',
                'status' => '1'
            ),

            array(
                'code' => 'Barclays ePDQ',
                'title' => 'barclays-epdq',
                'status' => '1'
            ),
            array(
                'code' => 'Beanstream',
                'title' => 'beanstream',
                'status' => '1'
            ),

            array(
                'code' => 'BKM Express',
                'title' => 'bkm',
                'status' => '1'
            ),

            array(
                'code' => 'BlueSnap',
                'title' => 'bluesnap',
                'status' => '1'
            ),
            array(
                'code' => 'Braintree',
                'title' => 'braintree',
                'status' => '1'
            ),

            array(
                'code' => 'Buckaroo',
                'title' => 'buckaroo',
                'status' => '1'
            ),

            array(
                'code' => 'CardGate',
                'title' => 'cardgate',
                'status' => '1'
            ),
        ); 
        \DB::table('payment_options')->insert($opt);
    }
}
/*
array(
                'code' => 'Braintree',
                'title' => 'braintree',
                'status' => '1'
            ),

            array(
                'code' => 'Buckaroo',
                'title' => 'buckaroo',
                'status' => '1'
            ),

            array(
                'code' => 'CardGate',
                'title' => 'cardgate',
                'status' => '1'
            ),




CardSave    ✓   -   omnipay/cardsave    Omnipay
CashBaBa    ✓   -   omnipay/cashbaba    Recursion Technologies Ltd
Checkout.com    ✓   -   fotografde/checkoutcom  fotograf.de
CloudBanking    ✓   -   cloudbanking/omnipay-cloudbanking   Cloudbanking
Coinbase    ✓   -   omnipay/coinbase    Omnipay
CoinGate    ✓   -   coingate/omnipay-coingate   CoinGate
Creditcall  ✓   -   meebio/omnipay-creditcall   John Jablonski
Cybersource     ✓   -   dioscouri/omnipay-cybersource   Dioscouri Design
Cybersource SOAP    ✓   -   dabsquared/omnipay-cybersource-soap     DABSquared
DataCash    ✓   -   digitickets/omnipay-datacash    DigiTickets
Datatrans   ✓   -   w-vision/datatrans  Dominik Pfaffenbauer
Datatrans   ✓   ✓   academe/omnipay-datatrans   Jason Judge
Docdata Payments    ✓   -   uskur/omnipay-docdata-payments  Uskur
Dummy   ✓   ✓   omnipay/dummy   Del
eGHL    ✓   ✓   dilab/omnipay-eghl  Xu Ding
ecoPayz     ✓   -   dercoder/omnipay-ecopayz    Alexander Fedra
EgopayRu    ✓   -   pinguinjkeke/omnipay-egopaymentru   Alexander Avakov
Elavon  ✓   -   lemonstand/omnipay-elavon   LemonStand
eWAY    ✓   ✓   omnipay/eway    Del
Fasapay     ✓   -   andreas22/omnipay-fasapay   Andreas Christodoulou
Fat Zebra   ✓   -   delatbabel/omnipay-fatzebra     Del
First Data  ✓   -   omnipay/firstdata   OmniPay
Flo2cash    ✓   -   guisea/omnipay-flo2cash     Aaron Guise
Free / Zero Amount  ✓   -   colinodell/omnipay-zero     Colin O’Dell
GiroCheckout    ✓   ✓   academe/omnipay-girocheckout    Jason Judge
Globalcloudpay  ✓   -   dercoder/omnipay-globalcloudpay     Alexander Fedra
GoCardless  ✓   -   omnipay/gocardless  Del
GovPayNet   ✓   -   omnipay/omnipay-govpaynet   FlexCoders
GVP (Garanti)   ✓   -   yasinkuyu/omnipay-gvp   Yasin Kuyu
Helcim  ✓   -   academe/omnipay-helcim  Jason Judge
iDram   -   ✓   ptuchik/omnipay-idram   Avik Aghajanyan
iPay88  ✓   ✓   dilab/omnipay-ipay88    Xu Ding
IfthenPay   ✓   -   ifthenpay/omnipay-ifthenpay     Rafael Almeida
Iyzico  ✓   -   yasinkuyu/omnipay-iyzico    Yasin Kuyu
Judo Pay    ✓   -   transportersio/omnipay-judopay  Transporters.io
Klarna Checkout     ✓   -   myonlinestore/omnipay-klarna-checkout   MyOnlineStore
Komerci (Rede, former RedeCard)     ✓   -   byjg/omnipay-komerci    João Gilberto Magalhães
Komoju  ✓   -   vink/omnipay-komoju     Danny Vink
Midtrans    ✓   ✓   dilab/omnipay-midtrans  Xu Ding
Magnius     -   ✓   fruitcake/omnipay-magnius   Fruitcake
Manual  ✓   -   omnipay/manual  Del
Migs    ✓   -   omnipay/migs    Omnipay
Mollie  ✓   ✓   omnipay/mollie  Barry vd. Heuvel
MOLPay  ✓   -   leesiongchan/molpay     Lee Siong Chan
MultiCards  ✓   -   incube8/omnipay-multicards  Del
MultiSafepay    ✓   -   omnipay/multisafepay    Alexander Deruwe
MyCard  ✓   -   xxtime/omnipay-mycard   Joe Chu
National Australia Bank (NAB) Transact  ✓   ✓   sudiptpa/omnipay-nabtransact    Sujip Thapa
NestPay (EST)   ✓   -   yasinkuyu/omnipay-nestpay   Yasin Kuyu
Netaxept (BBS)  ✓   -   omnipay/netaxept    Omnipay
Netbanx     ✓   -   omnipay/netbanx     Maks Rafalko
Neteller    ✓   -   dercoder/omnipay-neteller   Alexander Fedra
NetPay  ✓   -   netpay/omnipay-netpay   NetPay
Network Merchants Inc. (NMI)    ✓   -   mfauveau/omnipay-nmi    Matthieu Fauveau
Nocks   ✓   -   nocksapp/omnipay-nocks  Nocks
OnePay  ✓   ✓   dilab/omnipay-onepay    Xu Ding
Oppwa   ✓   ✓   vdbelt/omnipay-oppwa    Martin van de Belt
Payoo   ✓   ✓   dilab/omnipay-payoo     Xu Ding
Pacnet  ✓   -   mfauveau/omnipay-pacnet     Matthieu Fauveau
Pagar.me    ✓   -   descubraomundo/omnipay-pagarme  Descubra o Mundo
Paratika (Asseco)   ✓   -   yasinkuyu/omnipay-paratika  Yasin Kuyu
PayFast     ✓   -   omnipay/payfast     Omnipay
Payflow     ✓   -   omnipay/payflow     Del
PaymentExpress (DPS)    ✓   -   omnipay/paymentexpress  Del
PaymentExpress / DPS (A2A)  ✓   -   onlinesid/omnipay-paymentexpress-a2a    Sid
PaymentgateRu   ✓   ✓   pinguinjkeke/omnipay-paymentgateru  Alexander Avakov
PaymentSense    ✓   -   digitickets/omnipay-paymentsense    DigiTickets
PaymentWall     ✓   -   incube8/omnipay-paymentwall     Del
PayPal  ✓   ✓   omnipay/paypal  Del
PayPro  ✓   -   paypronl/omnipay-paypro     Fruitcake
PAYONE  ✓   ✓   academe/omnipay-payone  Jason Judge
Paysafecard     ✓   -   dercoder/omnipay-paysafecard    Alexander Fedra
Paysera     ✓   -   povils/omnipay-paysera  Povils
PaySimple   ✓   -   dranes/omnipay-paysimple    Dranes
PaySsion    ✓   -   inkedcurtis/omnipay-payssion    Curtis
PayTrace    ✓   -   softcommerce/omnipay-paytrace   Oleg Ilyushyn
PayU    ✓   -   omnipay/payu    efesaid
Pelecard    ✓   -   uskur/omnipay-pelecard  Uskur
Pin Payments    ✓   -   omnipay/pin     Del
Ping++  ✓   -   phoenixg/omnipay-pingpp     Huang Feng
POLi    ✓   -   burnbright/omnipay-poli     Sid
Portmanat   ✓   -   dercoder/omnipay-portmanat  Alexander Fedra
Posnet  ✓   -   yasinkuyu/omnipay-posnet    Yasin Kuyu
Postfinance     ✓   -   bummzack/omnipay-postfinance    Roman Schmid
Quickpay    ✓   -   nobrainerweb/omnipay-quickpay   Nobrainer Web
Realex  ✓   -   digitickets/omnipay-realex  DigiTickets
RedSys  ✓   -   nazka/sermepa-omnipay   Javier Sampedro
RentMoola   ✓   -   rentmoola/omnipay-rentmoola     Geoff Shaw
Sage Pay    ✓   ✓   omnipay/sagepay     Jason Judge
Sberbank    -   ✓   andrewnovikof/omnipay-sberbank  Andrew Novikov
SecPay  ✓   -   justinbusschau/omnipay-secpay   Justin Busschau
SecurePay   ✓   ✓   omnipay/securepay   Omnipay
Secure Trading  ✓   -   meebio/omnipay-secure-trading   John Jablonski
Sisow   ✓   ✓   fruitcakestudio/omnipay-sisow   Fruitcake
Skrill  ✓   -   alfaproject/omnipay-skrill  João Dias
Sofort  ✓   -   aimeoscom/omnipay-sofort    Aimeos GmbH
Spreedly    ✓   -   gregoriohc/omnipay-spreedly     Gregorio Hernández Caso
Square  ✓   -   transportersio/omnipay-square   Transporters.io
Stripe  ✓   ✓   omnipay/stripe  Del
TargetPay   ✓   -   omnipay/targetpay   Alexander Deruwe
UnionPay    ✓   ✓   lokielse/omnipay-unionpay   Loki Else
Vantiv  ✓   -   lemonstand/omnipay-vantiv   LemonStand
Veritrans   ✓   -   andylibrian/omnipay-veritrans   Andy Librian
Vindicia    ✓   -   vimeo/omnipay-vindicia  Vimeo
VivaPayments    ✓   -   delatbabel/omnipay-vivapayments     Del
WebMoney    ✓   -   dercoder/omnipay-webmoney   Alexander Fedra
WeChat  ✓   -   labs7in0/omnipay-wechat     7IN0’s Labs
WechatPay   ✓   ✓   lokielse/omnipay-wechatpay  Loki Else
WePay   ✓   -   collizo4sky/omnipay-wepay   Agbonghama Collins
Wirecard    ✓   ✓   igaponov/omnipay-wirecard   Igor Gaponov
Wirecard    ✓   -   academe/omnipay-wirecard    Jason Judge
Worldpay XML Direct Corporate Gateway   ✓   -   teaandcode/omnipay-worldpay-xml     Dave Nash
Worldpay XML Hosted Corporate Gateway   ✓   -   comicrelief/omnipay-worldpay-cg-hosted  Comic Relief
Worldpay Business Gateway   ✓   ✓   omnipay/worldpay    Omnipay
Yandex.Money    ✓   -   yandexmoney/omnipay     Roman Ananyev
Tpay    ✓   -   omnipay/tpay    Tpay.com





*/
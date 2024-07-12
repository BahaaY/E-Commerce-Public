<!DOCTYPE html>
<html lang="en">

<head>

    <title>Pages / Register</title>
    <?php 

        require_once '../main-head.php'; 
        permission_register_page($permission,$is_active,$is_verified);

    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/register.css">
    <script src="js/register.js"></script>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php' ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main class="main" id="main">

        <?php 
            if(isset($_SESSION[Session::$KEY_EC_LANG])){ 
                if(($_SESSION[Session::$KEY_EC_LANG]) == "ar" ){
                    $exclamation_mark="؟";
                    $dir_required="style='text-align:left !important'";
                }else{
                    $exclamation_mark="?";
                    $dir_required="";
                }
            }else{
                $exclamation_mark="?";
                $dir_required="style='text-align:right !important'";
            }
        ?>

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">
        <input type="hidden" id="key_enter_a_valid_email" value="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_A_VALID_EMAIL);  ?>">
        <input type="hidden" id="key_password_length" value="<?php echo $dictionary->get_lang($lang,$KEY_PASSWORD_LENGTH);  ?>">
        <input type="hidden" id="key_password_validation" value="<?php echo $dictionary->get_lang($lang,$KEY_PASSWORD_VALIDATION);  ?>">
        <input type="hidden" id="key_confirm_password_not_the_same" value="<?php echo $dictionary->get_lang($lang,$KEY_CONFIRM_PASSWORD_NOT_THE_SAME);  ?>">
        <input type="hidden" id="key_email_already_used" value="<?php echo $dictionary->get_lang($lang,$KEY_EMAIL_ALREADY_USED);  ?>">

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_CREATE_ACCOUNT); ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../products"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_PAGES); ?></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_CREATE_ACCOUNT); ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card mb-3">

                            <div class="card-body">

                                <div>
                                    <h5 class="card-title text-center pb-0 fs-4"><?php echo $dictionary->get_lang($lang,$KEY_CREATE_AN_ACCOUNT); ?></h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert"
                                            id="alert-success">
                                            <span class='text-alert-success'>Verification code has been sent to your
                                                account. You will be redirected to verification page after <span
                                                    id='text-alert-success-counter'>10</span>s</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                            id="alert-danger">
                                            <span id='text-alert-danger'></span>
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-2 d-none">
                                    <div class="col-md-6 col-sm-6 text-center">
                                        <div class="form-group">
                                            <button class="btn google-btn social-btn btn-danger" type="button">
                                                <span><i class="fa fa-google-plus"></i>&nbsp; <?php echo $dictionary->get_lang($lang,$KEY_SIGN_IN_WITH_GOOGLE); ?>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 text-center">
                                        <div class="form-group">
                                            <button class="btn facebook-btn social-btn btn-primary" type="button" id="btn_facebook">
                                                <span>
                                                    <i class="fa fa-facebook"></i>&nbsp; Sign In with
                                                    Facebook
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row"  <?php echo $dictionary->get_dir($lang); ?>>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_COUNTRY); ?></label> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                                    <span class="text-danger" id="error-country"></span>
                                                </div>
                                                <div class="col-md-12">
                                                    <select class="form-control" id="country" data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SELECT_A_COUNTRY);  ?>">
                                                        <option value="" data-capital=""></option>
                                                        <option value="AF" data-capital="Kabul">Afghanistan</option>
                                                        <option value="AX" data-capital="Mariehamn">Aland Islands</option>
                                                        <option value="AL" data-capital="Tirana">Albania</option>
                                                        <option value="DZ" data-capital="Algiers">Algeria</option>
                                                        <option value="AS" data-capital="Pago Pago">American Samoa
                                                        </option>
                                                        <option value="AD" data-capital="Andorra la Vella">Andorra
                                                        </option>
                                                        <option value="AO" data-capital="Luanda">Angola</option>
                                                        <option value="AI" data-capital="The Valley">Anguilla</option>
                                                        <option value="AG" data-capital="St. John's">Antigua and Barbuda
                                                        </option>
                                                        <option value="AR" data-capital="Buenos Aires">Argentina</option>
                                                        <option value="AM" data-capital="Yerevan">Armenia</option>
                                                        <option value="AW" data-capital="Oranjestad">Aruba</option>
                                                        <option value="AU" data-capital="Canberra">Australia</option>
                                                        <option value="AT" data-capital="Vienna">Austria</option>
                                                        <option value="AZ" data-capital="Baku">Azerbaijan</option>
                                                        <option value="BS" data-capital="Nassau">Bahamas</option>
                                                        <option value="BH" data-capital="Manama">Bahrain</option>
                                                        <option value="BD" data-capital="Dhaka">Bangladesh</option>
                                                        <option value="BB" data-capital="Bridgetown">Barbados</option>
                                                        <option value="BY" data-capital="Minsk">Belarus</option>
                                                        <option value="BE" data-capital="Brussels">Belgium</option>
                                                        <option value="BZ" data-capital="Belmopan">Belize</option>
                                                        <option value="BJ" data-capital="Porto-Novo">Benin</option>
                                                        <option value="BM" data-capital="Hamilton">Bermuda</option>
                                                        <option value="BT" data-capital="Thimphu">Bhutan</option>
                                                        <option value="BO" data-capital="Sucre">Bolivia</option>
                                                        <option value="BA" data-capital="Sarajevo">Bosnia and Herzegovina
                                                        </option>
                                                        <option value="BW" data-capital="Gaborone">Botswana</option>
                                                        <option value="BR" data-capital="Brasília">Brazil</option>
                                                        <option value="IO" data-capital="Diego Garcia">British Indian
                                                            Ocean Territory</option>
                                                        <option value="BN" data-capital="Bandar Seri Begawan">Brunei
                                                            Darussalam</option>
                                                        <option value="BG" data-capital="Sofia">Bulgaria</option>
                                                        <option value="BF" data-capital="Ouagadougou">Burkina Faso
                                                        </option>
                                                        <option value="BI" data-capital="Bujumbura">Burundi</option>
                                                        <option value="CV" data-capital="Praia">Cabo Verde</option>
                                                        <option value="KH" data-capital="Phnom Penh">Cambodia</option>
                                                        <option value="CM" data-capital="Yaoundé">Cameroon</option>
                                                        <option value="CA" data-capital="Ottawa">Canada</option>
                                                        <option value="KY" data-capital="George Town">Cayman Islands
                                                        </option>
                                                        <option value="CF" data-capital="Bangui">Central African Republic
                                                        </option>
                                                        <option value="TD" data-capital="N'Djamena">Chad</option>
                                                        <option value="CL" data-capital="Santiago">Chile</option>
                                                        <option value="CN" data-capital="Beijing">China</option>
                                                        <option value="CX" data-capital="Flying Fish Cove">Christmas
                                                            Island</option>
                                                        <option value="CC" data-capital="West Island">Cocos (Keeling)
                                                            Islands</option>
                                                        <option value="CO" data-capital="Bogotá">Colombia</option>
                                                        <option value="KM" data-capital="Moroni">Comoros</option>
                                                        <option value="CK" data-capital="Avarua">Cook Islands</option>
                                                        <option value="CR" data-capital="San José">Costa Rica</option>
                                                        <option value="HR" data-capital="Zagreb">Croatia</option>
                                                        <option value="CU" data-capital="Havana">Cuba</option>
                                                        <option value="CW" data-capital="Willemstad">Curaçao</option>
                                                        <option value="CY" data-capital="Nicosia">Cyprus</option>
                                                        <option value="CZ" data-capital="Prague">Czech Republic</option>
                                                        <option value="CI" data-capital="Yamoussoukro">Côte d'Ivoire
                                                        </option>
                                                        <option value="CD" data-capital="Kinshasa">Democratic Republic of
                                                            the Congo</option>
                                                        <option value="DK" data-capital="Copenhagen">Denmark</option>
                                                        <option value="DJ" data-capital="Djibouti">Djibouti</option>
                                                        <option value="DM" data-capital="Roseau">Dominica</option>
                                                        <option value="DO" data-capital="Santo Domingo">Dominican Republic
                                                        </option>
                                                        <option value="EC" data-capital="Quito">Ecuador</option>
                                                        <option value="EG" data-capital="Cairo">Egypt</option>
                                                        <option value="SV" data-capital="San Salvador">El Salvador
                                                        </option>
                                                        <option value="GQ" data-capital="Malabo">Equatorial Guinea
                                                        </option>
                                                        <option value="ER" data-capital="Asmara">Eritrea</option>
                                                        <option value="EE" data-capital="Tallinn">Estonia</option>
                                                        <option value="ET" data-capital="Addis Ababa">Ethiopia</option>
                                                        <option value="FK" data-capital="Stanley">Falkland Islands
                                                        </option>
                                                        <option value="FO" data-capital="Tórshavn">Faroe Islands</option>
                                                        <option value="FM" data-capital="Palikir">Federated States of
                                                            Micronesia</option>
                                                        <option value="FJ" data-capital="Suva">Fiji</option>
                                                        <option value="FI" data-capital="Helsinki">Finland</option>
                                                        <option value="MK" data-capital="Skopje">Former Yugoslav Republic
                                                            of Macedonia</option>
                                                        <option value="FR" data-capital="Paris">France</option>
                                                        <option value="PF" data-capital="Papeete">French Polynesia
                                                        </option>
                                                        <option value="GA" data-capital="Libreville">Gabon</option>
                                                        <option value="GM" data-capital="Banjul">Gambia</option>
                                                        <option value="GE" data-capital="Tbilisi">Georgia</option>
                                                        <option value="DE" data-capital="Berlin">Germany</option>
                                                        <option value="GH" data-capital="Accra">Ghana</option>
                                                        <option value="GI" data-capital="Gibraltar">Gibraltar</option>
                                                        <option value="GR" data-capital="Athens">Greece</option>
                                                        <option value="GL" data-capital="Nuuk">Greenland</option>
                                                        <option value="GD" data-capital="St. George's">Grenada</option>
                                                        <option value="GU" data-capital="Hagåtña">Guam</option>
                                                        <option value="GT" data-capital="Guatemala City">Guatemala
                                                        </option>
                                                        <option value="GG" data-capital="Saint Peter Port">Guernsey
                                                        </option>
                                                        <option value="GN" data-capital="Conakry">Guinea</option>
                                                        <option value="GW" data-capital="Bissau">Guinea-Bissau</option>
                                                        <option value="GY" data-capital="Georgetown">Guyana</option>
                                                        <option value="HT" data-capital="Port-au-Prince">Haiti</option>
                                                        <option value="VA" data-capital="Vatican City">Holy See</option>
                                                        <option value="HN" data-capital="Tegucigalpa">Honduras</option>
                                                        <option value="HK" data-capital="Hong Kong">Hong Kong</option>
                                                        <option value="HU" data-capital="Budapest">Hungary</option>
                                                        <option value="IS" data-capital="Reykjavik">Iceland</option>
                                                        <option value="IN" data-capital="New Delhi">India</option>
                                                        <option value="ID" data-capital="Jakarta">Indonesia</option>
                                                        <option value="IR" data-capital="Tehran">Iran</option>
                                                        <option value="IQ" data-capital="Baghdad">Iraq</option>
                                                        <option value="IE" data-capital="Dublin">Ireland</option>
                                                        <option value="IM" data-capital="Douglas">Isle of Man</option>
                                                        <option value="IL" data-capital="Jerusalem">Israel</option>
                                                        <option value="IT" data-capital="Rome">Italy</option>
                                                        <option value="JM" data-capital="Kingston">Jamaica</option>
                                                        <option value="JP" data-capital="Tokyo">Japan</option>
                                                        <option value="JE" data-capital="Saint Helier">Jersey</option>
                                                        <option value="JO" data-capital="Amman">Jordan</option>
                                                        <option value="KZ" data-capital="Astana">Kazakhstan</option>
                                                        <option value="KE" data-capital="Nairobi">Kenya</option>
                                                        <option value="KI" data-capital="South Tarawa">Kiribati</option>
                                                        <option value="KW" data-capital="Kuwait City">Kuwait</option>
                                                        <option value="KG" data-capital="Bishkek">Kyrgyzstan</option>
                                                        <option value="LA" data-capital="Vientiane">Laos</option>
                                                        <option value="LV" data-capital="Riga">Latvia</option>
                                                        <option value="LB" data-capital="Beirut">Lebanon</option>
                                                        <option value="LS" data-capital="Maseru">Lesotho</option>
                                                        <option value="LR" data-capital="Monrovia">Liberia</option>
                                                        <option value="LY" data-capital="Tripoli">Libya</option>
                                                        <option value="LI" data-capital="Vaduz">Liechtenstein</option>
                                                        <option value="LT" data-capital="Vilnius">Lithuania</option>
                                                        <option value="LU" data-capital="Luxembourg City">Luxembourg
                                                        </option>
                                                        <option value="MO" data-capital="Macau">Macau</option>
                                                        <option value="MG" data-capital="Antananarivo">Madagascar</option>
                                                        <option value="MW" data-capital="Lilongwe">Malawi</option>
                                                        <option value="MY" data-capital="Kuala Lumpur">Malaysia</option>
                                                        <option value="MV" data-capital="Malé">Maldives</option>
                                                        <option value="ML" data-capital="Bamako">Mali</option>
                                                        <option value="MT" data-capital="Valletta">Malta</option>
                                                        <option value="MH" data-capital="Majuro">Marshall Islands</option>
                                                        <option value="MQ" data-capital="Fort-de-France">Martinique
                                                        </option>
                                                        <option value="MR" data-capital="Nouakchott">Mauritania</option>
                                                        <option value="MU" data-capital="Port Louis">Mauritius</option>
                                                        <option value="MX" data-capital="Mexico City">Mexico</option>
                                                        <option value="MD" data-capital="Chișinău">Moldova</option>
                                                        <option value="MC" data-capital="Monaco">Monaco</option>
                                                        <option value="MN" data-capital="Ulaanbaatar">Mongolia</option>
                                                        <option value="ME" data-capital="Podgorica">Montenegro</option>
                                                        <option value="MS" data-capital="Little Bay, Brades, Plymouth">
                                                            Montserrat</option>
                                                        <option value="MA" data-capital="Rabat">Morocco</option>
                                                        <option value="MZ" data-capital="Maputo">Mozambique</option>
                                                        <option value="MM" data-capital="Naypyidaw">Myanmar</option>
                                                        <option value="NA" data-capital="Windhoek">Namibia</option>
                                                        <option value="NR" data-capital="Yaren District">Nauru</option>
                                                        <option value="NP" data-capital="Kathmandu">Nepal</option>
                                                        <option value="NL" data-capital="Amsterdam">Netherlands</option>
                                                        <option value="NZ" data-capital="Wellington">New Zealand</option>
                                                        <option value="NI" data-capital="Managua">Nicaragua</option>
                                                        <option value="NE" data-capital="Niamey">Niger</option>
                                                        <option value="NG" data-capital="Abuja">Nigeria</option>
                                                        <option value="NU" data-capital="Alofi">Niue</option>
                                                        <option value="NF" data-capital="Kingston">Norfolk Island</option>
                                                        <option value="KP" data-capital="Pyongyang">North Korea</option>
                                                        <option value="MP" data-capital="Capitol Hill">Northern Mariana
                                                            Islands</option>
                                                        <option value="NO" data-capital="Oslo">Norway</option>
                                                        <option value="OM" data-capital="Muscat">Oman</option>
                                                        <option value="PK" data-capital="Islamabad">Pakistan</option>
                                                        <option value="PW" data-capital="Ngerulmud">Palau</option>
                                                        <option value="PA" data-capital="Panama City">Panama</option>
                                                        <option value="PG" data-capital="Port Moresby">Papua New Guinea
                                                        </option>
                                                        <option value="PY" data-capital="Asunción">Paraguay</option>
                                                        <option value="PE" data-capital="Lima">Peru</option>
                                                        <option value="PH" data-capital="Manila">Philippines</option>
                                                        <option value="PN" data-capital="Adamstown">Pitcairn</option>
                                                        <option value="PL" data-capital="Warsaw">Poland</option>
                                                        <option value="PT" data-capital="Lisbon">Portugal</option>
                                                        <option value="PR" data-capital="San Juan">Puerto Rico</option>
                                                        <option value="QA" data-capital="Doha">Qatar</option>
                                                        <option value="CG" data-capital="Brazzaville">Republic of the
                                                            Congo</option>
                                                        <option value="RO" data-capital="Bucharest">Romania</option>
                                                        <option value="RU" data-capital="Moscow">Russia</option>
                                                        <option value="RW" data-capital="Kigali">Rwanda</option>
                                                        <option value="BL" data-capital="Gustavia">Saint Barthélemy
                                                        </option>
                                                        <option value="KN" data-capital="Basseterre">Saint Kitts and Nevis
                                                        </option>
                                                        <option value="LC" data-capital="Castries">Saint Lucia</option>
                                                        <option value="VC" data-capital="Kingstown">Saint Vincent and the
                                                            Grenadines</option>
                                                        <option value="WS" data-capital="Apia">Samoa</option>
                                                        <option value="SM" data-capital="San Marino">San Marino</option>
                                                        <option value="ST" data-capital="São Tomé">Sao Tome and Principe
                                                        </option>
                                                        <option value="SA" data-capital="Riyadh">Saudi Arabia</option>
                                                        <option value="SN" data-capital="Dakar">Senegal</option>
                                                        <option value="RS" data-capital="Belgrade">Serbia</option>
                                                        <option value="SC" data-capital="Victoria">Seychelles</option>
                                                        <option value="SL" data-capital="Freetown">Sierra Leone</option>
                                                        <option value="SG" data-capital="Singapore">Singapore</option>
                                                        <option value="SX" data-capital="Philipsburg">Sint Maarten
                                                        </option>
                                                        <option value="SK" data-capital="Bratislava">Slovakia</option>
                                                        <option value="SI" data-capital="Ljubljana">Slovenia</option>
                                                        <option value="SB" data-capital="Honiara">Solomon Islands</option>
                                                        <option value="SO" data-capital="Mogadishu">Somalia</option>
                                                        <option value="ZA" data-capital="Pretoria">South Africa</option>
                                                        <option value="KR" data-capital="Seoul">South Korea</option>
                                                        <option value="SS" data-capital="Juba">South Sudan</option>
                                                        <option value="ES" data-capital="Madrid">Spain</option>
                                                        <option value="LK"
                                                            data-capital="Sri Jayawardenepura Kotte, Colombo">Sri Lanka
                                                        </option>
                                                        <option value="PS" data-capital="Ramallah">State of Palestine
                                                        </option>
                                                        <option value="SD" data-capital="Khartoum">Sudan</option>
                                                        <option value="SR" data-capital="Paramaribo">Suriname</option>
                                                        <option value="SZ" data-capital="Lobamba, Mbabane">Swaziland
                                                        </option>
                                                        <option value="SE" data-capital="Stockholm">Sweden</option>
                                                        <option value="CH" data-capital="Bern">Switzerland</option>
                                                        <option value="SY" data-capital="Damascus">Syrian Arab Republic
                                                        </option>
                                                        <option value="TW" data-capital="Taipei">Taiwan</option>
                                                        <option value="TJ" data-capital="Dushanbe">Tajikistan</option>
                                                        <option value="TZ" data-capital="Dodoma">Tanzania</option>
                                                        <option value="TH" data-capital="Bangkok">Thailand</option>
                                                        <option value="TL" data-capital="Dili">Timor-Leste</option>
                                                        <option value="TG" data-capital="Lomé">Togo</option>
                                                        <option value="TK" data-capital="Nukunonu, Atafu,Tokelau">Tokelau
                                                        </option>
                                                        <option value="TO" data-capital="Nukuʻalofa">Tonga</option>
                                                        <option value="TT" data-capital="Port of Spain">Trinidad and
                                                            Tobago</option>
                                                        <option value="TN" data-capital="Tunis">Tunisia</option>
                                                        <option value="TR" data-capital="Ankara">Turkey</option>
                                                        <option value="TM" data-capital="Ashgabat">Turkmenistan</option>
                                                        <option value="TC" data-capital="Cockburn Town">Turks and Caicos
                                                            Islands</option>
                                                        <option value="TV" data-capital="Funafuti">Tuvalu</option>
                                                        <option value="UG" data-capital="Kampala">Uganda</option>
                                                        <option value="UA" data-capital="Kiev">Ukraine</option>
                                                        <option value="AE" data-capital="Abu Dhabi">United Arab Emirates
                                                        </option>
                                                        <option value="GB" data-capital="London">United Kingdom</option>
                                                        <option value="US" data-capital="Washington, D.C.">United States
                                                            of America</option>
                                                        <option value="UY" data-capital="Montevideo">Uruguay</option>
                                                        <option value="UZ" data-capital="Tashkent">Uzbekistan</option>
                                                        <option value="VU" data-capital="Port Vila">Vanuatu</option>
                                                        <option value="VE" data-capital="Caracas">Venezuela</option>
                                                        <option value="VN" data-capital="Hanoi">Vietnam</option>
                                                        <option value="VG" data-capital="Road Town">Virgin Islands
                                                            (British)</option>
                                                        <option value="VI" data-capital="Charlotte Amalie">Virgin Islands
                                                            (U.S.)</option>
                                                        <option value="EH" data-capital="Laayoune">Western Sahara</option>
                                                        <option value="YE" data-capital="Sana'a">Yemen</option>
                                                        <option value="ZM" data-capital="Lusaka">Zambia</option>
                                                        <option value="ZW" data-capital="Harare">Zimbabwe</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" <?php echo $dictionary->get_dir($lang); ?>>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_EMAIL); ?></label> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                                    <span class="text-danger" id="error-email"></span>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="email" class="form-control" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_EMAIL);  ?>" id="email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row" <?php echo $dictionary->get_dir($lang); ?>>
                                                <div class="col-md-6">
                                                    <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_PASSWORD); ?></label> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                                    <span class="text-danger" id="error-password"></span>
                                                </div>
                                                <div class="col-md-12" style="direction: ltr !important">
                                                    <div class="input-group" id="show_hide_password">
                                                        <input class="form-control" type="password" id="password"
                                                            placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PASSWORD);  ?>"  <?php echo $dictionary->get_dir($lang); ?>>
                                                        <div
                                                            class="input-group-addon input-group-addon-password d-flex align-items-center p-2">
                                                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row" <?php echo $dictionary->get_dir($lang); ?>>
                                                <div class="col-md-6">
                                                    <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_CONFIRM_PASSWORD); ?></label> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                                    <span class="text-danger" id="error-confirm-password"></span>
                                                </div>
                                                <div class="col-md-12" style="direction: ltr !important">
                                                    <div class="input-group" id="show_hide_confirm_password">
                                                        <input class="form-control" type="password" id="confirm-password"
                                                            placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_CONFIRM_PASSWORD);  ?>" <?php echo $dictionary->get_dir($lang); ?>>
                                                        <div
                                                            class="input-group-addon input-group-addon-confirm-password d-flex align-items-center p-2">
                                                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-2 text-center">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" id="btn_register"
                                                name="btn_register"><i class='bi bi-door-open mr-2'></i><?php echo $dictionary->get_lang($lang,$KEY_CREATE_ACCOUNT); ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col small">
                                        <?php echo $dictionary->get_lang($lang,$KEY_ALREADY_HAVE_AN_ACCOUNT); ?><?php echo $exclamation_mark ?> <a href="../login" class="link-primary"><?php echo $dictionary->get_lang($lang,$KEY_LOGIN); ?></a>
                                    </div>
                                    <div class="col small text-right">
                                        <a role="button" class="link-primary" id="btn_google"><?php echo $dictionary->get_lang($lang,$KEY_SIGN_IN_WITH_GOOGLE); ?></a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>

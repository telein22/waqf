<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);

?>

<section class=" terms-service">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="text-center mb-5"><?= $lang('faq'); ?></h1>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse" aria-expanded="false" aria-controls="flush-collapse">
                                How to Sign in or Sign up
                            </button>
                        </h2>
                        <div id="flush-collapse" class="accordion-collapse collapse" aria-labelledby="flush-heading" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                After completing the registration in the website, please complete your profile to be able to attend or create a session, a call, or to be able to receive and send mail.
                                Click the Menu icon at the top of the page, then choose profile and click Edit Profile icon. It will show for you the General Information, your social media links, and you can modify your account password.</div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                How to search about different specialties
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-parent="#accordionFlushExample">
                            <div class="accordion-body">You can explore the different specialties by clicking the Menu icon, then choose Search or Explore. Or explore all specialty by <a class="text-primary" href="<?= URL::full('dashboard') ?>">click here</a>. </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                How to create a Tele-Sessions
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                You can view sessions by clicking Menu icon and then click My Tele-Session. It will show your outgoing and incoming sessions, and you can also create a session by filling in the required info: Name of the session, a description of your session, and the appropriate date for you. Note: typing the time in minutes, and determining the number of attendees for the session.You can share your session or any other sessions by clicking on the Share icon, as we allow you to share it via WhatsApp or email or copy the link and share it in any of your social media account.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                How to create a Tele-Calls
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                You can view the calls by clicking Menu icon and then click My Tele-Calls. It will show your incoming and outgoing calls, and you can also create a call by filling in the appropriate time and date for you, and the desired amount from the beneficiaries. The duration of the call will be 15 minutes. You can scheduled several calls at different times according to what suits your schedule.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                How to create a Tele-Mails
                            </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                You can see the messages by clicking Menu icon then click My Tele-Mails.<br />
                                It will show your outgoing and incoming messages, and you can also manage your message by specifying the price you want from the beneficiaries and enable Tele-Mails.<br />
                                Be aware that if you receive messages, you must respond to the messages within a maximum period of 48 hours, to be able to receive the requested amount. If you did not respond within 48 hours, the amount will be refunded to the requester.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                                How to know my earnings
                            </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            You can view your earnings by clicking Menu icon and then click My Earnings.<br />
You will see all your orders, if you created a session or a call or a mail. <br />
The total is the amount of completed orders.<br />
Ongoing is the orders that are still under procedure.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                                How can I find out all the available sessions for booking
                            </button>
                        </h2>
                        <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-parent="#accordionFlushExample">
                            <div class="accordion-body">
                            You can see all the available sessions for booking by clicking Menu icon and then click Book Tele-session, a list of the available sessions for booking will appear for you by the people you following, or you can also view the other available sessions. 
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                                Contact Us
                            </button>
                        </h2>
                        <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven" data-parent="#accordionFlushExample">
                            <div class="accordion-body">You can send your inquiries by clicking Menu icon and then click Customer Support, writing your inquiry and choosing how to be sending via e-mail or WhatsApp. You can contact us thru our E-Mail: support@telein.net </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<define header_css>
    <style>
        body {
            background: #fafafb;
        }

        .accordion-item {
            margin-bottom: 25px;
            border-bottom: none;
            -webkit-box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
            box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
            border-radius: 5px !important;
            overflow: hidden;
        }
    </style>
</define>
<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);
?>
<div class="container" style="margin-top: 6%;">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center mb-5">
                <img src="<?= URL::asset('\Application\Assets\images\logo.svg') ?>" class="img-fluid" width="100" alt="">
                <h3 class="mt-3 text-center mb-3 font-size-18">
                    <p>
                        <span class="d-none" id="host-waiting-message"><?= $lang('waiting_for_host_to_start_this_call') ?></span>
                    <div>
                        <img src="<?= URL::asset('\Application\Assets\images\loader.gif') ?>" class="img-fluid" id="loader" width="25px" height="25px" alt="">
                    </div>
                    </p>
                    <p id="time-remaining"><?= $lang('time_remaining_to_start_call')?></p>
                    <div class="countdown" style="margin-top: 0px;"></div>
                    <p id="advisor-late-message" class="d-none"><?= $lang('waiting_for_the_host_to_start_the_call')?></p>
                </h3>
            </div>
        </div>
    </div>
</div>


<define footer_js>
    <script>
        var callId = "<?= $id ?>",
            //slotId = "<?//= $slot_id ?>//",
            callDate = "<?= $date ?>",
            isAdvisor = "<?= $isAdvisor?>",
            callJoinURL = '<?= URL::full('ajax/calls/join') ?>',
            callStartAndJoinURL = '<?= URL::full('ajax/calls/startAndJoin') ?>',
            joinUrl = isAdvisor ? callStartAndJoinURL : callJoinURL;

        if (!isAdvisor) {
            $('#host-waiting-message').removeClass('d-none');
        }

        function joinCall() {
            $.ajax({
                url: joinUrl,
                data: {
                    id: callId
                },
                beforeSend: function () {

                },
                success: function (data) {
                    if (data.info !== 'success') {
                        toast('danger', Call.labels.error_title, data.payload.msg);
                        if (data.payload.key === 'advisor_not_started') {
                            $('#advisor-late-message').removeClass('d-none');
                        }

                        return;
                    }

                    setTimeout(function() {
                        if (isAdvisor) {
                            window.location.href = data.payload.Advisor_url;
                            return;
                        }

                        window.location.href = data.payload.JoinMeetingURL;
                    });
                },
                complete: function () {
                }
            })
        }

        function countDown(sessionDate, fn) {
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone: 'Asia/Riyadh',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const currentDate = new Date();
            const formattedDateTime = formatter.format(currentDate);

            var today = new Date(formattedDateTime),
                diff = sessionDate.getTime() - today.getTime();
            if (diff > 0) {
                var interval = setInterval(function () {
                    var today = new Date(),
                        diff = sessionDate.getTime() - today.getTime(),
                        minutes = (new Date(diff)).getMinutes(),
                        seconds = (new Date(diff)).getSeconds();

                    if ((minutes === 0 && seconds === 0) || minutes === 59) {
                        $('.countdown').html('00:00');
                        clearInterval(interval);

                        if (!isAdvisor) {
                            $('#advisor-late-message').removeClass('d-none');
                            $('.call').css({"display": "block"});
                            window.isCountdownActive = false;
                        }

                        setTimeout(function() {
                            fn();
                        }, 3000);
                    } else {
                        seconds = (seconds < 10) ? '0' + seconds : seconds;
                        minutes = (minutes < 10) ? '0' + minutes : minutes;

                        if (minutes >= 0 && seconds >= 0) {
                            $('.countdown').html(`${minutes}:${seconds}`);
                        }
                    }
                }, 1000);
            } else {
                window.isCountdownActive = false;
            }
        }

        countDown(new Date(callDate), function() {
            if (isAdvisor) {
                joinCall()
            }
        });

        var diff = new Date(callDate) - new Date();

        if (diff <= 0) {
            $('#time-remaining').addClass('d-none');
        }

        var pusher = new Pusher("<?= \System\Core\Application::config()->Pusher['key'] ?>", {
            cluster: "<?= \System\Core\Application::config()->Pusher['cluster'] ?>",
        });

        var channel = pusher.subscribe(`notifications.call.${callId}`);
        channel.bind('call_started', function (e) {
            joinCall()
        });
    </script>
</define>
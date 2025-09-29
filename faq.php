<?php
// Include your common header file
include 'common/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - MSME GLOBAL</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        html, body { overflow-x: hidden; }

        .faq-page {
            background: linear-gradient(to bottom, #d9a7ff, #fbc2eb, #ffb88c);
            color: #000;
        }

        .faq-header {
            background-color: #b565d9;
            padding: 20px;
            text-align: center;
            margin-top: 80px;
        }

        .faq-header h1 {
            margin: 0;
            font-size: 2rem;
            color: white;
        }

        .faq-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }

        .faq-item {
            background-color: #b565d9;
            color: white;
            border-radius: 10px;
            margin: 8px 0;
            overflow: hidden;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        .faq-question {
            padding: 12px 15px;
            font-size: 1rem;
            font-weight: bold;
        }

        .faq-answer {
            background: #f9f9f9;
            color: #000;
            padding: 0 15px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            padding: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .faq-question {
                font-size: 0.95rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body class="faq-page">

<div class="faq-header">
    <h1>FAQ</h1>
</div>

<div class="faq-container">

    <div class="faq-item">
        <div class="faq-question">What is MSME GLOBAL?</div>
        <div class="faq-answer">MSME GLOBAL is an online platform connecting Micro, Small, and Medium Enterprises worldwide to showcase their products, network, and generate leads.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">How can I list my business on MSME GLOBAL?</div>
        <div class="faq-answer">You can register on our website, fill in your business details, upload relevant documents, and choose a subscription plan to start listing.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What are the plans & pricing options available?</div>
        <div class="faq-answer">We offer Basic, Trusted, and Premium plans with varying benefits. Pricing details are available on our Plans page.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What are the benefits of Trusted Plan?</div>
        <div class="faq-answer">Trusted Plan offers higher visibility, access to verified leads, priority listing, and better networking opportunities.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Is there a limit to the listings?</div>
        <div class="faq-answer">Basic plans have limited listings, while Trusted and Premium plans offer unlimited listings.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Can I upgrade from Basic to Trusted Plan later?</div>
        <div class="faq-answer">Yes, you can upgrade anytime by paying the difference in plan prices.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">How will customers or visitors find my business?</div>
        <div class="faq-answer">Customers can search by category, location, or keywords on our platform to find your business profile.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Is there any commission charged on business generated?</div>
        <div class="faq-answer">No, we do not charge commission. You only pay for your subscription plan.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Will my personal information be shared?</div>
        <div class="faq-answer">Your personal data is protected and only relevant business details are displayed to the public.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">How can I edit or update my listing?</div>
        <div class="faq-answer">Log in to your account and use the ‘Edit Listing’ option to make updates anytime.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What if I face a technical issue?</div>
        <div class="faq-answer">You can contact our support team via email, phone, or live chat for assistance.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Can I cancel my subscription and get a refund?</div>
        <div class="faq-answer">Refunds are subject to our cancellation policy. Please check the terms before subscribing.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Can I get featured or promoted on MSME?</div>
        <div class="faq-answer">Yes, featured promotions are available for Trusted and Premium users.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Is there any offline meetups or networking opportunity?</div>
        <div class="faq-answer">We organize business networking events and seminars periodically for members.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Who is behind MSME GLOBAL?</div>
        <div class="faq-answer">MSME GLOBAL is managed by a team of industry experts and technology professionals passionate about empowering small businesses.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">How is MSME GLOBAL different from other platforms like IndiaMART & JustDIAL?</div>
        <div class="faq-answer">Unlike others, MSME GLOBAL focuses exclusively on MSMEs and offers a global reach with verified leads and networking features.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What kind of businesses are already listed on MSME GLOBAL?</div>
        <div class="faq-answer">We have businesses from manufacturing, services, exports, handicrafts, and more.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Can I list my business if I already use platforms like IndiaMART or JustDIAL?</div>
        <div class="faq-answer">Yes, you can list on MSME GLOBAL even if you are registered elsewhere.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Who can register on MSME GLOBAL?</div>
        <div class="faq-answer">Any micro, small, or medium enterprise, as well as suppliers and service providers, can register.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What is the ‘Post a Requirement’ feature on MSME GLOBAL?</div>
        <div class="faq-answer">It allows you to post what you need so suppliers and service providers can respond with offers.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">How can I post a requirement?</div>
        <div class="faq-answer">Log in, go to the requirements section, and fill in the details of your requirement.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Can I edit or update my posted requirement later?</div>
        <div class="faq-answer">Yes, you can edit or delete any requirement from your dashboard.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Who can view these posted requirements?</div>
        <div class="faq-answer">Only registered suppliers and trusted members can view posted requirements.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Is there a limit to how many requirements I can post?</div>
        <div class="faq-answer">Basic plan users have limited postings, while Trusted and Premium users have unlimited posts.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Where do Trusted Users find the posted requirements?</div>
        <div class="faq-answer">Trusted users can access the ‘Requirements’ section on their dashboard.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What if I am a Basic Plan user – will I get leads?</div>
        <div class="faq-answer">Yes, but lead access may be limited compared to higher plans.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Is there any extra cost to access these leads?</div>
        <div class="faq-answer">No extra cost for Trusted and Premium users. Basic users may have limited free access.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Can non-business users also register to track their requirement status?</div>
        <div class="faq-answer">Yes, non-business users can register to track requirements or find suppliers.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Are these leads verified?</div>
        <div class="faq-answer">Yes, we verify all leads before making them available to our members.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Will I be notified when a new requirement matches my category?</div>
        <div class="faq-answer">Yes, you will receive email and dashboard notifications for matching requirements.</div>
    </div>

</div>

<script>
    // Toggle answer on click
    document.querySelectorAll('.faq-item').forEach(item => {
        item.addEventListener('click', () => {
            item.classList.toggle('active');
        });
    });
</script>

<?php
// Include your common footer file
include 'common/footer.php';
?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Student Registration</title>

    <style>
        .otp-input {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .otp-input input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .otp-input input:focus {
            border-color: #007bff;
            outline: none;
        }
        .countdown-timer {
            text-align: center;
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>OTP Verification</header>

        <form action="insert_user.php" method="post" onsubmit="combineOTP()">
            <div class="form first">
                <div class="details personal">
                    <span class="title">Insert OTP</span>

                    <div class="fields otp-input">
                        <input type="number" id="otp1" maxlength="1" required oninput="moveToNext(this, 'otp2')">
                        <input type="number" id="otp2" maxlength="1" required oninput="moveToNext(this, 'otp3')">
                        <input type="number" id="otp3" maxlength="1" required oninput="moveToNext(this, 'otp4')">
                        <input type="number" id="otp4" maxlength="1" required oninput="moveToNext(this, 'otp5')">
                        <input type="number" id="otp5" maxlength="1" required oninput="moveToNext(this, '')">
                    </div>

                    

                    <input type="hidden" name="otp" id="fullOtp">
                    <button class="submit">
                        <span class="btnText">Verify</span>
                        <i class="uil uil-navigator"></i>
                    </button>
                    
                    <p>Back to <a href="login_user.html" style="color: blue;">Login</a></p>
                </div>
                
            </div>
        </form>
        <div class="countdown-timer" id="countdown"></div>
    </div>

    <script>
        // Countdown logic using localStorage
        const countdownElement = document.getElementById("countdown");
        const redirectUrl = "registration_user.html";
        const countdownDuration = 180000; // 3  mins in milliseconds

        function startCountdown() {
            const now = Date.now();
            let endTime = localStorage.getItem("countdownEndTime");

            if (!endTime || now > endTime) {
                endTime = now + countdownDuration;
                localStorage.setItem("countdownEndTime", endTime);
            }

            const interval = setInterval(() => {
                const remainingTime = endTime - Date.now();
                if (remainingTime <= 0) {
                    clearInterval(interval);
                    localStorage.removeItem("countdownEndTime");
                    window.location.href = redirectUrl;
                } else {
                    const minutes = Math.floor(remainingTime / 60000);
                    const seconds = Math.floor((remainingTime % 60000) / 1000);
                    countdownElement.textContent = `Insert the OTP in: ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
                }
            }, 1000);
        }

        // Function to move to the next input field
        function moveToNext(current, nextFieldID) {
            if (current.value.length === 1 && nextFieldID) {
                document.getElementById(nextFieldID).focus();
            }
        }

        // Function to combine OTP fields into one hidden input
        function combineOTP() {
            const otp = [
                document.getElementById("otp1").value,
                document.getElementById("otp2").value,
                document.getElementById("otp3").value,
                document.getElementById("otp4").value,
                document.getElementById("otp5").value
            ].join("");
            document.getElementById("fullOtp").value = otp;
        }

        // Start the countdown when the page loads
        startCountdown();
    </script>
</body>
</html>

@props([
    'title' => __('Verify Your Email Address'),
    'description' => __('Please enter the 6-digit verification code we sent to your email address.'),
    'lastOtpSentAt' => null,
    'isForgot' => null,
])

<section class="flex items-center justify-center min-h-[80vh] bg-gray-100 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div
        class="max-w-lg w-full space-y-8 p-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ $verifyRoute }}" class="space-y-6" id="otp-form">
            @csrf

            <h1 class="text-3xl font-extrabold text-center text-gray-900 dark:text-white">
                {{ $title }}
            </h1>

            <p class="mt-4 text-center text-gray-600 dark:text-gray-400">
                {{ $description }}
            </p>

            @if (session('status'))
                <div class="text-sm font-medium text-green-600 dark:text-green-400 text-center">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->has('resend_timer'))
                <div class="text-sm font-medium text-red-600 dark:text-red-400 text-center">
                    {{ $errors->first('resend_timer') }}
                </div>
            @endif

            <div class="mt-6">
                @if (isset($isForgot))
                    <input id="forgot" name="forgot" type="hidden" value="{{ $isForgot }}"
                        autocomplete="forgot">
                @endif

                {{-- Assuming x-input-label is a Blade component you have --}}
                <label for="otp"
                    class="block text-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Verification Code') }}</label>


                <div class="flex justify-center space-x-2 otp-inputs">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" id="otp-{{ $i }}" name="otp_digit[]" maxlength="1"
                            inputmode="numeric" autocomplete="one-time-code"
                            class="otp-digit w-12 h-12 text-center text-2xl font-bold rounded-md border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition-all duration-200 ease-in-out shadow-sm"
                            style="caret-color: transparent;" onfocus="this.select()">
                    @endfor
                    {{-- Hidden input to store the combined OTP value for submission --}}
                    <input type="hidden" name="otp" id="hidden-otp-input">
                </div>

                {{-- Assuming x-input-error is a Blade component you have --}}
                @error('otp')
                    <p class="mt-2 text-center text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div
                class="flex flex-col sm:flex-row items-center justify-between mt-8 space-y-4 sm:space-y-0 sm:space-x-4">
                <button type="button" id="resend-otp-button"
                    class="btn btn-sm btn-soft uppercase tracking-widest btn-accent"
                    data-resend-route="{{ $resendRoute }}"
                    @if (isset($lastOtpSentAt)) data-last-sent-timestamp="{{ $lastOtpSentAt }}" @endif>
                    {{ __('Resend Code') }}
                </button>

                {{-- Assuming x-primary-button is a Blade component you have --}}
                <button type="submit" class="btn btn-sm text-white uppercase tracking-widest btn-primary">
                    {{ __('Verify') }}
                </button>
            </div>
        </form>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const otpInputs = document.querySelectorAll('.otp-digit');
                const hiddenOtpInput = document.getElementById('hidden-otp-input');
                const form = document.getElementById('otp-form');
                const resendButton = document.getElementById('resend-otp-button');
                // Changed cooldown to 60 seconds (1 minute) for more realistic behavior
                const resendCooldownSeconds = `{{ $cooldown ?? 1 }}`;

                let countdownInterval;

                // Function to update the hidden OTP input
                function updateHiddenOtp() {
                    let combinedOtp = '';
                    otpInputs.forEach(input => {
                        combinedOtp += input.value;
                    });
                    hiddenOtpInput.value = combinedOtp;
                }

                // Function to start the resend cooldown timer
                function startResendCountdown(lastSentTimestamp) {
                    const now = Math.floor(Date.now() / 1000); // Current time in seconds
                    const elapsedTime = now - lastSentTimestamp;
                    let remainingTime = resendCooldownSeconds - elapsedTime;

                    if (remainingTime <= 0) {
                        // Cooldown has passed, enable button immediately
                        resendButton.disabled = false;
                        resendButton.textContent = '{{ __('Resend Code') }}';
                        clearInterval(countdownInterval); // Ensure no lingering interval
                        return;
                    }

                    resendButton.disabled = true;
                    resendButton.textContent = `{{ __('Resend Code') }} (${remainingTime}s)`;

                    clearInterval(countdownInterval); // Clear any existing interval to prevent multiple timers

                    countdownInterval = setInterval(() => {
                        remainingTime--;
                        if (remainingTime > 0) {
                            resendButton.textContent = `{{ __('Resend Code') }} (${remainingTime}s)`;
                        } else {
                            clearInterval(countdownInterval);
                            resendButton.disabled = false;
                            resendButton.textContent = '{{ __('Resend Code') }}';
                        }
                    }, 1000);
                }

                // Initialize countdown if a last sent timestamp is available
                const initialLastSentTimestamp = resendButton.dataset.lastSentTimestamp;
                if (initialLastSentTimestamp) {
                    startResendCountdown(parseInt(initialLastSentTimestamp));
                }

                // Add event listener for the resend button (using Axios)
                resendButton.addEventListener('click', async () => {
                    if (resendButton.disabled) {
                        return; // Do nothing if button is disabled (during cooldown or sending)
                    }

                    // Temporarily disable the button to prevent multiple clicks
                    resendButton.disabled = true;
                    resendButton.textContent = '{{ __('Sending...') }}';

                    try {
                        const resendRoute = resendButton.dataset.resendRoute;
                        // Use a consistent variable name for the hidden input ID
                        const forgotInput = document.getElementById('forgot');
                        const forgotValue = forgotInput ? forgotInput.value :
                        undefined; // Use undefined if not present

                        // Using Axios to send the POST request
                        const response = await axios.post(resendRoute, {
                            forgot: forgotValue
                        });

                        const data = response.data; // Axios automatically parses JSON

                        if (data.success) {
                            toastr.success(data.message || 'OTP sent successfully!');
                            if (data.last_sent_at) {
                                startResendCountdown(data.last_sent_at);
                            } else {
                                // If server doesn't return last_sent_at, assume now
                                startResendCountdown(Math.floor(Date.now() / 1000));
                            }
                        } else {
                            // Handle specific non-success responses if needed, otherwise general error
                            toastr.error(data.message || 'Failed to send OTP. Please try again.');
                            resendButton.disabled = false;
                            resendButton.textContent = '{{ __('Resend Code') }}';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        // Axios error response object has more details
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            if (error.response.status === 401) {
                                toastr.error('You are not authenticated. Please log in again.');
                                // window.location.href = `{{ $loginUrl }}`;
                                return;
                            } else if (error.response.status === 429) {
                                // Specific handling for too many requests (rate limiting)
                                toastr.error(error.response.data.message ||
                                    'Too many requests. Please try again later.');
                            } else if (error.response.data && error.response.data.message) {
                                // Display specific error message from server
                                toastr.error(error.response.data.message);
                            } else {
                                toastr.error(
                                    'An error occurred while sending OTP. Please try again.');
                            }
                        } else if (error.request) {
                            // The request was made but no response was received (e.g., network error)
                            toastr.error('No response from server. Please check your network connection.');
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            toastr.error('An unexpected error occurred. Please try again.');
                        }

                        // Re-enable button and reset text only if no redirect happened
                        resendButton.disabled = false;
                        resendButton.textContent = '{{ __('Resend Code') }}';
                    }
                });


                otpInputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => {
                        // Filter out non-numeric input and only take the first character
                        if (e.data && !/^\d$/.test(e.data)) {
                            input.value = ''; // Clear invalid input
                            updateHiddenOtp();
                            return;
                        }
                        if (input.value.length >
                            1) { // If more than one character (e.g., paste single digit)
                            input.value = input.value.charAt(0);
                        }

                        if (input.value.length === 1 && index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                        updateHiddenOtp();
                    });

                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace') {
                            if (input.value.length === 0 && index > 0) {
                                // If current input is empty, move focus to previous input and clear it
                                otpInputs[index - 1].focus();
                                otpInputs[index - 1].value = ''; // Clear previous input on backspace
                            }
                            // Allow default backspace behavior to clear current input
                        } else if (e.key === 'ArrowLeft' && index > 0) {
                            otpInputs[index - 1].focus();
                        } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    });

                    // Select all text on focus for easier typing/overwriting
                    input.addEventListener('focus', function() {
                        this.select();
                    });

                    // Only apply paste listener to the first input
                    if (index === 0) {
                        input.addEventListener('paste', (e) => {
                            e.preventDefault();
                            const pasteData = e.clipboardData.getData('text').trim();
                            // Check if pasted data is exactly 6 digits and numeric
                            if (pasteData.length === 6 && /^\d+$/.test(pasteData)) {
                                otpInputs.forEach((otpInput, i) => {
                                    otpInput.value = pasteData[i];
                                });
                                // Focus the last input after pasting
                                otpInputs[otpInputs.length - 1].focus();
                                updateHiddenOtp();
                            } else if (pasteData.length > 0) {
                                // Clear inputs and alert if invalid paste
                                otpInputs.forEach(otpInput => otpInput.value = '');
                                alert('Please paste a 6-digit numeric code.');
                                updateHiddenOtp();
                            }
                        });
                    }
                });

                // Ensure hidden OTP input is updated on form submission
                form.addEventListener('submit', () => {
                    updateHiddenOtp();
                });

                // Initial update of hidden OTP in case of pre-filled values (less common for OTP)
                updateHiddenOtp();
            });
        </script>
    @endpush
</section>

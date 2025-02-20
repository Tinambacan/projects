<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

// var recognition = new webkitSpeechRecognition();

// recognition.continuous = true;
// recognition.interimResults = true;

// var isRecognitionActive = false; // Flag to track if recognition is currently active

// function startRecognition() {
//     if (!isRecognitionActive) {
//         isRecognitionActive = true; // Set the flag to indicate recognition is active
//         recognition.start();
//     }
// }

// function stopRecognition() {
//     if (isRecognitionActive) {
//         recognition.stop();
//         isRecognitionActive = false; // Set the flag to indicate recognition is not active
//     }
// }

// recognition.onresult = function(event) {
//     const result = event.results[event.results.length - 1][0].transcript;
//     $('#input').val(result); // Set the recognized text in the input field

//     setTimeout(function() {
//         if (isRecognitionActive) {
//             stopRecognition();
//             $('#button-submit').click(); // Trigger the form submission
//         }
//     }, 3000);
// };

// $('#startRecognition').click(function() {
//     startRecognition();
// });

// var chatContainer = document.getElementById('content-box');

// function scrollToBottom() {
//     chatContainer.scrollTop = chatContainer.scrollHeight;
// }

// $('#input').keypress(function(event) {
//     if (event.keyCode === 13) {
//         event.preventDefault();
//         $('#button-submit').click();
//     }

// });

// $('#button-submit').on('click', function() {
//     var $value = $('#input').val(); // Use var to declare the variable
//     $('#content-box').append(`
//         <div class="flex justify-end w-full">
//             <div class="flex p-2 text-end gap-2 justify-end animate-fade-in-up">
//                 <div id="" class="bg-white border border-gray-400 rounded-lg shadow-2xl flex  p-2 w-auto">
//                     ` + $value + `
//                 </div>
//                 <div class="my-auto">
//                     <i class="fa-solid fa-circle-user text-gray-500 mx-auto" style="font-size: 25px;"></i>
//                 </div>
//             </div>
//         </div>
//     `);
//     let responseData = [];

//     $('#Hide-bot').hide();
//     $.ajax({
//         type: 'POST',
//         url: '/send-question',
//         data: {
//             'input': $value
//         },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         dataType: 'text',
//         success: function(data) {
//             console.log(data);
//             responseData = responseData.concat(data);
//             $('#content-box').append(`
//                 <div id="responseChat" class="flex justify-start">
//                     <div class="flex flex-col gap-2">
//                         <div class="flex p-2 text-start gap-2 justify-start animate-fade-in-up w-full">
//                             <div class="my-auto mx-auto">
//                                 <img class="mx-auto drop-shadow-2xl mb-1"
//                                     src="{{ URL('images/Chatbot.png') }}"
//                                     style="height: 2.5rem; width: 2.5rem">
//                             </div>
//                             <div id="" class="bg-white border border-gray-400 rounded-lg shadow-2xl flex p-2 md:w-80 w-56 ">
//                                 ${data}
//                             </div>
//                             <div class="flex gap-3">
//                                 <button class="speak-button text-gray-500 hover:text-red-800  rounded"><i class="fa-solid fa-volume-high"></i></button>
//                                 <button class="stop-button text-gray-500 hover:text-red-800 rounded"><i class="fa-solid fa-circle-stop"></i></button>
//                             </div>
//                         </div>
//                     </div>
//                 </div>
//             `);
//             $value = $('input').val('');
//             scrollToBottom();
//         },
//         error: function(xhr, status, error) {
//             console.log("Error Status: " + status);
//             console.log("Error Message: " + error);
//         }
//     });

//     $('#input').val('');

// })
</script>
<template>
    <AppLayout title="Chatbot" @theme-changed="updateSelectedTheme">
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
            integrity="sha512-******************************"
            crossorigin="anonymous"
        />

        <div class="flex mt-6 mb-10 px-4 md:mt-28 md:px-56">
            <div
                class="h-auto md:h-96 w-full bg-white rounded-xl shadow-2xl flex flex-col"
                :class="['theme-container', selectedTheme]"
            >
                <div class="p-2 md:h-96 flex">
                    <div
                        class="bg-gray-100 border-2 border-gray-400 w-full md:h-80 h-96 rounded-lg overflow-auto p-3"
                        id="content-box"
                        ref="chatContainer"
                        :class="['theme-container', selectedTheme]"
                    >
                        {{ message }}
                        <div
                            class="flex items-center justify-center mt-32"
                            id="Hide-bot"
                        >
                            <h1
                                class="text-gray-500 text-3xl font-bold animate-fade-in-down"
                            >
                                Hi, I'm CIEBOT!
                            </h1>
                        </div>
                    </div>
                </div>

                <div
                    class="flex relative bg-white rounded-xl pb-2"
                    :class="['theme-container', selectedTheme]"
                >
                    <ApplicationLogo
                        imgSource="/images/Chatbot.png"
                        imgStyle="height: 4rem; width: 4rem"
                    />
                    <!-- <input
                        autocomplete="off"
                        type="text"
                        id="input"
                        v-model="inputValue"
                        @keyup.enter="handleKeyPress"
                        class="block rounded-full w-full text-sm text-gray-900 border-4 border-gray-500 md:ml-0 ml-2 p-4 focus:outline-red-900 pr-10"
                        placeholder="Ask Something..."
                        required
                    /> -->

                    <input
                        autocomplete="off"
                        type="text"
                        id="input"
                        v-model="inputValue"
                        @keyup.enter="handleKeyPress"
                        class="block rounded-full w-full text-sm text-gray-900 border-4 border-gray-500 md:ml-0 ml-2 p-4 focus:outline-red-900 pr-10"
                        placeholder="Ask Something..."
                        required
                        :class="['theme-container', selectedTheme]"
                    />
                    <FontAwesomeIcon
                        :class="['theme-container', selectedTheme]"
                        @click="handleSubmit"
                        icon="paper-plane"
                        type="submit"
                        class="cursor-pointer hover:text-red-900 text-gray-500 pb-2"
                        id="button-submit"
                        style="
                            font-size: 18px;
                            position: absolute;
                            right: 46px;
                            top: 50%;
                            transform: translateY(-50%);
                        "
                    />

                    <FontAwesomeIcon
                        :class="['theme-container', selectedTheme]"
                        icon="microphone"
                        type="submit"
                        id="startRecognition"
                        class="mt-6 m-1 text-xl cursor-pointer hover:text-red-900 text-gray-500"
                        @click="toggleRecognition"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import axios from "axios";
export default {
    data() {
        return {
            inputValue: "",
            responseData: [],
            message: "",
            csrfToken: null,
            isRecognitionActive: false,
            selectedTheme:  this.getStoredTheme() || 'theme1',
        };
    },
    mounted() {
        // Fetch the CSRF token from the server
        this.fetchCsrfToken();
        this.setupSpeechRecognition();
    },
    components: {
        FontAwesomeIcon,
        AppLayout,
    },
    methods: {
        updateSelectedTheme(theme) {
            this.selectedTheme = theme;
        },

        getStoredTheme() {
            return localStorage.getItem("currentTheme") || 'theme1';
        },
        attachEventListeners() {
            const buttonElement = document.getElementById("button-submit");
            if (buttonElement) {
                buttonElement.addEventListener("click", this.handleButtonClick);
            }
        },
        async fetchCsrfToken() {
            try {
                const response = await axios.get("/csrf-token");
                this.csrfToken = response.data.csrfToken;
            } catch (error) {
                console.error("Error fetching CSRF token:", error);
            }
        },
        handleKeyPress() {
            this.handleSubmit();
        },

        setupSpeechRecognition() {
            this.recognition = new webkitSpeechRecognition();
            this.recognition.continuous = true;
            this.recognition.interimResults = true;

            this.recognition.onresult = (event) => {
                const result =
                    event.results[event.results.length - 1][0].transcript;
                this.inputValue = result; // Set the recognized text in the input field

                setTimeout(() => {
                    if (this.isRecognitionActive) {
                        this.stopRecognition();
                        this.handleSubmit(); // Trigger the form submission
                    }
                }, 3000);
            };
        },
        startRecognition() {
            if (!this.isRecognitionActive) {
                this.isRecognitionActive = true; // Set the flag to indicate recognition is active
                this.recognition.start();
            }
        },
        stopRecognition() {
            if (this.isRecognitionActive) {
                this.recognition.stop();
                this.isRecognitionActive = false; // Set the flag to indicate recognition is not active
            }
        },
        toggleRecognition() {
            if (this.isRecognitionActive) {
                this.stopRecognition();
            } else {
                this.startRecognition();
            }
        },

        async handleSubmit() {
            const chatContainer = this.$refs.chatContainer;
            this.appendMessage(
                "flex justify-end",
                "rounded-md",
                this.inputValue
                // "fas fa-user-circle text-gray-500 mr-2 fa-2x"
            );
            try {
                const response = await this.sendQuestion(this.inputValue);
                console.log("Response in handleSubmit:", response);
            } catch (error) {
                console.error("Error in handleSubmit:", error);
            }
            // Hide the bot
            this.hideBot();
            this.inputValue = "";

            // Scroll to the bottom of the content box
            this.scrollToBottom(chatContainer);

            // Attach event listeners to speak and stop buttons
            this.attachEventListeners();
        },
        hideBot() {
            $("#Hide-bot").hide();
        },
        appendMessage(
            containerClass,
            messageClass,
            content,
            userIconClass = null,
            buttonHTML
        ) {
            const messageHTML = `
          <div class="${containerClass}">
            <div class="flex ${messageClass}">

              <div class="my-auto "
                ${userIconClass ? "" : content}
              </div>
             
              <div class="rounded-lg shadow-2xl flex p-2 md:w-full w-52 border border-gray-400 animate-fade-in-up">
               
                ${content}
              </div>
             

            </div>
          </div>
        `;

            // Use Vue.js refs to access the underlying DOM element
            this.$refs.chatContainer.insertAdjacentHTML(
                "beforeend",
                messageHTML
            );
        },

        async sendQuestion(inputValue) {
            try {
                const headers = {
                    "X-CSRF-TOKEN": this.csrfToken,
                };

                const response = await axios.post(
                    "/send-question",
                    { input: inputValue },
                    { headers }
                );

                console.log("Full Response:", response);
                const content =
                    response && response.data && response.data.content
                        ? response.data.content
                        : "";
                const imageURL = "images/Chatbot.png";
                const imageElement = document.createElement("img");
                imageElement.src = imageURL;

                const buttonHTML = `<button class="speak-button" data-content="${content}"><i class="fas fa-volume-up text-gray-500 hover:text-red-800 ml-2"></i></button>
                                    <button class="stop-button">
                                        <i class="fas fa-stop text-gray-500 hover:text-red-800 ml-2"></i>
                                    </button>`;
                const img = `<img src="images/Chatbot.png" class="my-auto" style="height: 2.5rem; width: 2.5rem"> `;
                const messageContentDiv = `<div class=" rounded-lg flex p-2  ${
                    content === "Hello" || content === "Hi" ? "" : "w-80"
                } mx-auto drop-shadow-2xl mb-1">${content}</div>`;
                const fullContent = img + messageContentDiv + buttonHTML;
                this.appendMessage(
                    "flex justify-start",
                    ` p-2  ${
                        content === "Hello" || content === "Hi" ? "" : "w-80"
                    }`,
                    fullContent,

                    "mx-auto drop-shadow-2xl mb-1 "
                );
                const speakButtons = document.querySelectorAll(".speak-button");
                const stopButtons = document.querySelectorAll(".stop-button");
                const synth = window.speechSynthesis;
                speakButtons.forEach((speakButton, index) => {
                    speakButton.addEventListener("click", () => {
                        console.log("Button clicked");
                        if (synth.speaking) {
                            console.error(
                                "SpeechSynthesisUtterance is already speaking"
                            );
                            return;
                        }

                        console.log("Content:", speakButton.dataset.content);

                        const text = new SpeechSynthesisUtterance(
                            speakButton.dataset.content
                        );
                        synth.speak(text);
                    });
                });
                stopButtons.forEach((stopButton) => {
                    stopButton.addEventListener("click", () => {
                        synth.cancel(); // Stop the speech synthesis
                    });
                });
            } catch (error) {
                console.error("Error sending question:", error);
                throw error;
            }
        },

        scrollToBottom(chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        },
    },
};
</script>

<style>
.theme-container {
    transition: background-color 0.5s ease-in-out;
}

.theme1 {
    background-color: #ffffff;
    border-color: #4d4d4d;
    color: #4d4d4d;
}

.theme2 {
    background-color: #f2ecbe;
    border-color: #c08261;
    color: #9a3b3b;
}

.theme3 {
    background-color: #393646;
    border-color: #f4eee0;
    color: #f4eee0;
}
.theme4 {
    background-color: #eee2de;
    border-color: #2b2a4c;
    color: #2b2a4c;
}
.gen-bg {
    background-color: #ffffff;
}
</style>

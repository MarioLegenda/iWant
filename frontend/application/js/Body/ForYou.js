import {RepositoryFactory} from "../services/repositoryFactory";

const ContactForm = {
    data: function() {
        return {
            name: null,
            message: null,
            error: false,
            success: false,
            failure: false,
        }
    },
    template: `
        <div class="ContactForm">
            <div class="FormRow">
                <label>Your name (not mandatory)</label>
                <input type="text" v-model="name"/>
            </div>
            
            <div class="FormRow">
                <label>Your message/error/bug (mandatory)</label>
                <textarea v-model="message"></textarea>
            </div>
            
            <p v-if="error" class="Error">Message is mandatory</p>
            
            <p v-if="failure" class="Error">
                I apologise but something went wrong and the message could not be 
                sent. Please, reload the page and try again or try sending an email
                to iwouldlikeapplication@gmail.com
            </p>
            
            <p v-if="success" class="Success">Thank you for your feedback. I will contact you as soon as possible.</p>

            <div class="ForRow">
                <button @click="submit">Send</button>
            </div>
        </div>
    `,
    methods: {
        submit() {
            this.error = false;
            this.success = false;
            this.failure = false;

            if (isEmpty(this.message)) {
                this.error = true;

                return false;
            }

            this.$repository.AppRepository.postSendContactMessage(JSON.stringify({
                message: {
                    name: this.name,
                    message: this.message
                }
            }), (r) => {
                if (r.statusCode === 200) {
                    this.success = true;

                    this.name = null;
                    this.message = null;

                    return false;
                }

                this.failure = true;

                return false;
            }, () => {
                this.failure = true;

                return false;
            })
        }
    }
};


export const ForYou = {
    template: `
        <transition name="fade">
            <div class="ForYou">
                <div class="TextPanel">
                    <div class="TextBlock">
                        <p>
                            Hello, my name is Mario and I am a software developer. Two years ago, I started to work on an SDK (Software Development Kit) for PHP
                            programming language for working with eBay APIs. At the time I started to work on it, there
                            was one already created but it did not have all the features that eBay APIs provide
                            and did not handle validation and errors gracefully. It was also cumbersome to work with
                            so I decided to create my own eBay API SDK as an open source project. At that time, it
                            was just something interesting and exiting for me, as a PHP developer, to do. While I was working
                            on it, I noticed that eBay has a lot of room to improve on its searching capability on their own
                            sites. I played with it for a while and realised that I can, by utilising the eBay API, construct
                            the search results that are more fine grained that eBay. But, at that time, I had to quit working
                            on it because I had to focus more on my professional career as a software engineer.
                        </p>
                        
                        <p>
                            In between then and now, eBay has changed the design of some of their sites but did not
                            change anything related on handling the search results more better. By reading the comments on eBay community
                            forums, it has actually made the search more worse that it was. So I decided to create an application
                            that search every eBay site and tries to give you the results that you actually want to see. I put an 
                            emphasis on tries because it is still in Beta and it needs a lot more work on it.
                        </p>
                        
                        <p>
                            This application is created for professional eBay sellers, resellers, shops and frequent buyers.
                            But because this is still a prototype and the that I am working on it alone, it might have bugs.
                            That is the reason why I need your help. I am here to make your life easier so if you find this application
                            useful in your every day work, send me your feedback. Anything from a new feature that will be helpful for you,
                            an error or a bug that you find or just to say hello, don't hesitate to contact me.
                        </p>
                        
                        <p>
                            You can send me a message with the contact form below or directly on my email <span class="Highlight">iwouldlikeapplication@gmail.com</span>.
                            I will try to respond to you in the shortest time possible.
                        </p>
                    </div>
                </div>
                
                <contact-form></contact-form>
            </div>
</transition>
    `,
    components: {
        'contact-form': ContactForm,
    }
};
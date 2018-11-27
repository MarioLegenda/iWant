export const ActionNameValueContainer = {
    data: function() {
        return {
            showDescription: false
        }
    },
    template: `<div @click="onShowDescription" class="Row NameValueContainer IsHoverable">
                   <p class="Name">{{name}}</p>
                   <p class="Value">{{value}}</p>
                                
                   <i v-bind:class="toggleChevronClass"></i>
                   <transition name="fade">
                       <p v-if="showDescription && description !== false" class="NameValueDescription">{{description}}</p>
                   </transition>
                   
                   <transition name="fade">
                       <slot v-if="showDescription" name="description"></slot>
                   </transition>
               </div>`,
    props: ['name', 'value', 'description'],
    computed: {
        toggleChevronClass: function() {
            return (this.showDescription === false) ? 'ActionIdentifier fas fa-chevron-right' : 'ActionIdentifier fas fa-chevron-down'
        }
    },
    methods: {
        onShowDescription() {
            this.showDescription = !this.showDescription;
        }
    }
};
export const ActionNameValueContainer = {
    data: function() {
        return {
            showDescription: false
        }
    },
    template: `<div @click="onShowDescription" class="NameValueRow NameValueContainer IsHoverable">
                   <div v-if="isNameDefined() === false">
                       <p class="FullWidthName">{{computedValue}}</p>
                   </div>
                   
                   <div v-else-if="isNameDefined() === true">
                       <p class="Name">{{computedName}}</p>
                       <p class="Value">{{computedValue}}</p>
                   </div>
                                
                   <i v-bind:class="toggleChevronClass"></i>
                   
                   <transition name="fade">
                       <slot v-if="showDescription && isDescriptionDefined() === true" name="description"></slot>
                   </transition>
                   
               </div>`,
    created() {
        if (isEmpty(this.name) && isEmpty(this.value)) {
            throw new Error('\'name\' and \'value\' passed properties have to have a string value');
        }
    },
    props: ['name', 'value', 'description'],
    computed: {
        toggleChevronClass: function() {
            return (this.showDescription === false) ? 'ActionIdentifier fas fa-chevron-right' : 'ActionIdentifier fas fa-chevron-down'
        },
        computedName: function() {
            return this.name;
        },

        computedValue: function() {
            return this.value;
        }
    },
    methods: {
        onShowDescription() {
            this.showDescription = !this.showDescription;
        },

        isNameDefined() {
            if (isEmpty(this.name)) {
                return false;
            }

            return true;
        },

        isDescriptionDefined() {
            if (isEmpty(this.description)) {
                return false;
            }

            return true;
        }
    }
};
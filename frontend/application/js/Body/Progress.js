import {IWouldLike} from "../global/IWouldLike";

export const Progress = {
    template: `
        <transition name="fade">
            <div class="Progress">
                <div class="TextPanel">
                    <div class="TextBlock">
                        <p>
                            <span class="Highlight">
                                This section will show all the changes and new features added to
                                <i-would-like /> over time after the first release. Every time I implement
                                a new feature, improve an existing one, it will be explained here. Since 
                                <i-would-like /> is still a prototype (Beta), I will have to decide will it
                                be in a form of a blog, a simple list of new features or something else so stay
                                tuned...
                            </span>
                        </p>
                    </div>
                </div>
            </div>
</transition>
    `,
    components: {
        'i-would-like': IWouldLike,
    }
};
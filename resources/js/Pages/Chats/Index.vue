<template>
        <div class="h-screen hidden md:grid md:grid-cols-6 lg:grid-cols-8">
            <div class="md:col-span-2 lg:col-span-2 border-r border-neutral-300">
                <div class="flex flex-col justify-center space-y-4">
                    <div class="flex pl-2 justify-center items-center pt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>

                        <p class="font-bold uppercase tracking-widest text-xl">Messenger</p>
                    </div>
                    <div class="flex justify-center items-center px-2">
                        <input type="text" class="w-full input input-bordered rounded-full focus:outline-none" placeholder="Search" />
                    </div>
                    <div class="flex items-center w-full">
                        <div class="flex-grow border-t border-neutral-300"></div>
                        <div class="mx-2 text-center">
                            <p>Favorites</p>
                        </div>
                        <div class="flex-grow border-t border-neutral-300"></div>
                    </div>
                    <div class="flex justify-center">
                        <p class="label-text">You don't have any favorites chats yet.</p>
                    </div>
                    <div class="flex items-center w-full mt-2">
                        <div class="flex-grow border-t border-neutral-300"></div>
                        <div class="mx-2 text-center">
                            <p>All Messages</p>
                        </div>
                        <div class="flex-grow border-t border-neutral-300"></div>
                    </div>
                    <div class="flex-grow overflow-auto" style="height: calc(100vh - 232px); overflow-y: auto; scrollbar-width: thin;">
<!--                        <p class="label-text text-center">Your messages list is empty.</p>-->
                        <Link
                            :href="route('chats.show', chat.id)"
                            :key="chat.id"
                            v-for="chat in chats.data"
                            class="grid grid-cols-6 gap-4 p-3 hover:bg-secondary transform duration-300 hover:text-white"
                            :class="{'bg-secondary text-white': chat.id === $page.props.id}"
                        >
                            <div class="flex justify-center items-center">
                                <img :src="chat.user.profile_photo_url" class="w-12 h-12 rounded-full" alt=""/>
                            </div>
                            <div class="col-span-4">
                                <div
                                    class="text-sm truncate font-bold"
                                    :class="{'text-white': chat.id === $page.props.id}"
                                >
                                    {{ chat.user.name }}
                                </div>
                                <div class="text-xs truncate">
                                    <span
                                        class="text-info"
                                        v-if="chat.latest_message.from_id === $page.props.auth.user.id"
                                    >You: </span>
                                    {{chat.latest_message.body}}
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="$page.props.id" class="md:col-span-4 lg:col-span-6 bg-secondary/10 flex flex-col">
                <div class="flex justify-between w-full bg-base-100 p-4">
                    <div>username</div>
                    <div class="space-x-2">
                        <button class="btn btn-circle btn-secondary btn-sm">
                            <font-awesome-icon icon="fa-solid fa-star" size="lg" />
                        </button>
                        <button class="btn btn-circle btn-secondary btn-sm">
                            <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" />
                        </button>
                    </div>
                </div>
                <div class="flex-grow overflow-auto p-4" style="height: calc(100vh - 200px); overflow-y: auto; scrollbar-width: thin;">
                    <slot />
                </div>
                <div class="w-full p-4 pt-0" style="position: sticky; bottom: 0;">
                    <div class="relative -mt-2">
                        <label for="file" class="btn btn-secondary btn-circle btn-sm absolute left-2 top-2">
                            <font-awesome-icon icon="fa-solid fa-paperclip" size="lg" />
                        </label>
                        <input
                            @change="form.attachment = $event.target.files[0]"
                            name="attachment"
                            type="file"
                            class="hidden"
                            id="file"
                        />
                        <button class="btn btn-secondary bg-secondary/30 border-secondary/30 btn-circle btn-sm absolute left-12 top-2">
                            <font-awesome-icon icon="fa-solid fa-smile" />
                        </button>
                        <textarea
                            v-model="form.body"
                            rows="1"
                            placeholder="Type a message..."
                            class="pl-24 w-full textarea textarea-bordered rounded-full resize-none focus:outline-none"
                        />
                        <button
                            @click="sendMessage"
                            class="absolute right-2 top-2 btn btn-secondary btn-circle btn-sm"
                        >
                            <font-awesome-icon icon="fa-solid fa-paper-plane" size="lg" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
    id: Number,
    chats: Object,
    sendMessage: Function,
    form: Object
});
</script>

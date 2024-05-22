<template>
    <div
        class="flex h-full overflow-hidden"
        :class="isShow ? 'md:ml-[300px] lg:ml-[400px]' : ''"
    >
        <div v-if="isShow" class="z-10 w-full bg-base-200">
            <div
                class="fixed bottom-0 left-0 top-0 flex h-full w-full flex-col border-r border-neutral-200
                md:w-[300px] lg:w-[400px]"
            >
                <div class="flex flex-col justify-center space-y-4">
                    <Link :href="route('chats.index')" class="flex pl-2 justify-center items-center pt-2 hover:bg-secondary/90 bg-secondary text-white p-2 h-16">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>

                        <p class="font-bold uppercase tracking-widest text-xl">Messenger</p>
                    </Link>
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
                    <div class="flex-grow overflow-auto" style="height: calc(100vh - 260px); overflow-y: auto; scrollbar-width: thin;">
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
        </div>
    </div>

    <div
        v-if="$page.props.id"
        class="flex-grow"
        :class="isShow ? 'md:ml-[300px] lg:ml-[400px]' : ''"
    >
        <div
            class="bg-secondary/10"
            :class="isOpen ? 'md:mr-[300px] lg:mr-[400px]' : ''"
        >
            <div class="flex justify-between items-center w-full bg-base-100 p-2">
                <div class="flex items-center space-x-2 h-12">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full w-12 h-12">
                    <p class="font-semibold">{{ user.name }}</p>
                </div>
                <div class="space-x-2">
                    <button class="btn btn-circle btn-secondary btn-sm">
                        <font-awesome-icon icon="fa-solid fa-star" size="lg" />
                    </button>
                    <button
                        @click="isOpen = !isOpen"
                        class="btn btn-circle btn-secondary btn-sm"
                    >
                        <font-awesome-icon icon="fa-solid fa-circle-info" size="lg" />
                    </button>
                </div>
            </div>
            <div>
                <slot />
            </div>
            <div class="w-full p-4 pt-0" style="position: sticky; bottom: 0;">
                <form @submit.prevent="sendMessage" class="relative -mt-2">
                    <label for="file" class="btn btn-secondary btn-circle btn-sm absolute left-2 top-2">
                        <font-awesome-icon icon="fa-solid fa-paperclip" size="lg" />
                    </label>
                    <input
                        @change="handleFileUpload"
                        name="attachment"
                        type="file"
                        class="hidden"
                        accept="image/*"
                        id="file"
                    />

                    <button
                        @click="toggleEmojiPicker"
                        class="btn btn-secondary bg-secondary/30 border-secondary/30 btn-circle btn-sm absolute left-12 top-2"
                    >
                        <font-awesome-icon icon="fa-solid fa-smile" />
                    </button>

                    <EmojiPicker
                        v-if="showEmojiPicker"
                        class="absolute left-12 bottom-12"
                        :native="true"
                        @select="onSelectEmoji"
                    />

                    <textarea
                        v-model="form.body"
                        rows="1"
                        placeholder="Type a message..."
                        class="pl-24 w-full textarea textarea-bordered rounded-full resize-none focus:outline-none"
                    />

                    <button
                        type="submit"
                        class="absolute right-2 top-2 btn btn-secondary btn-circle btn-sm"
                    >
                        <font-awesome-icon icon="fa-solid fa-paper-plane" size="lg" />
                    </button>
                    <div v-if="attachmentPreview.value" class="indicator absolute left-2 bottom-12">
                        <button
                            @click="removeAttachment"
                            class="indicator-item badge badge-error w-6 h-6"
                        >
                            <font-awesome-icon icon="fa-solid fa-times" size="xs"/>
                        </button>
                        <img :src="attachmentPreview.value" alt="Attachment Preview" class="object-cover w-24 h-24 border rounded overflow-hidden">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div
        class="flex h-full overflow-hidden"
        :class="isOpen ? 'md:ml-[300px] lg:ml-[400px]' : ''"
    >
        <div v-if="isOpen" class="z-10 w-full bg-base-200">
            <div class="fixed bottom-0 right-0 top-0 flex h-full w-full flex-col border-r border-neutral-200 md:w-[300px] lg:w-[400px]">
                <div class="flex items-center space-y-4 border border-neutral-200 p-2 h-16">
                    <div class="w-full flex justify-between items-center">
                        <div class="font-semibold">User Details</div>
                        <div>
                            <button
                                @click="isOpen = !isOpen"
                                class="btn btn-circle btn-secondary btn-sm"
                            >
                                <font-awesome-icon icon="fa-solid fa-xmark" size="lg" />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-2">
                    <div class="flex justify-center">
                        <img :src="user.profile_photo_url" :alt="user.name" class="w-24 h-24 rounded-full">
                    </div>
                    <div class="text-center font-semibold mt-2">
                        {{ user.name }}
                    </div>
                </div>
                <div class="flex items-center w-full mt-2">
                    <div class="flex-grow border-t border-neutral-300"></div>
                    <div class="mx-2 text-center">
                        <p>Shared Photos</p>
                    </div>
                    <div class="flex-grow border-t border-neutral-300"></div>
                </div>
                <div v-for="attachment in attachments.data" :key="attachment.id">
                    <div class="grid grid-cols-3 gap-2 p-2 mx-2 text-center">
                        <img :src="attachment.attachment" class="rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Link, usePage} from '@inertiajs/vue3';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {onBeforeMount, onMounted, onUnmounted, ref} from "vue";
import 'vue3-emoji-picker/css'
import EmojiPicker from 'vue3-emoji-picker'

const props = defineProps({
    chats: Object,
    sendMessage: Function,
    form: Object,
    user: Object,
    attachmentPreview: Object,
    attachments: Object,
});

let showEmojiPicker = ref(false);

const onSelectEmoji = (emoji) => {
    props.form.body += emoji.i;
    showEmojiPicker.value = false;
};

const toggleEmojiPicker = () => {
    showEmojiPicker.value = !showEmojiPicker.value;
};

const isShow = ref(false);
const isOpen = ref(false);
const updateIsShow = () => {
    if (window.innerWidth >= 768) {
        isShow.value = true;
    } else isShow.value = !usePage().props.id;
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        props.form.attachment = file;
        props.attachmentPreview.value = URL.createObjectURL(file);
    } else {
        props.form.attachment = null;
        props.attachmentPreview.value = '';
    }
};

const removeAttachment = () => {
    props.form.attachment = null;
    props.attachmentPreview.value = '';
};

onBeforeMount(() => {
    updateIsShow();
});

onMounted(() => {
    window.addEventListener('resize', updateIsShow);
});

onUnmounted(() => {
    window.removeEventListener('resize', updateIsShow);
});
</script>

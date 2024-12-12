<template>
    <div class="w-full p-4 pt-0" style="position: sticky; bottom: 0">
        <form @submit.prevent="$emit('send')" class="relative -mt-2">
            <!-- File Upload -->
            <label for="file" class="btn btn-secondary btn-circle btn-sm absolute left-2 top-2">
                <PaperclipIcon />
            </label>
            <input
                @change="$emit('file-upload', $event)"
                name="attachment"
                type="file"
                class="hidden"
                accept="image/*"
                id="file"
            />

            <!-- Emoji Picker -->
            <button
                @click.prevent="$emit('toggle-emoji')"
                class="btn btn-secondary bg-secondary/30 border-secondary/30 btn-circle btn-sm absolute left-12 top-2"
            >
                <SmileIcon />
            </button>

            <EmojiPicker
                v-if="showEmojiPicker"
                class="absolute left-12 bottom-12"
                :native="true"
                @select="$emit('select-emoji', $event)"
            />

            <!-- Message Input -->
            <textarea
                v-model="form.body"
                rows="1"
                placeholder="Type a message..."
                class="pl-24 w-full textarea textarea-bordered rounded-full resize-none focus:outline-none"
                @keydown.enter.prevent="handleEnterPress"
            />

            <!-- Send Button -->
            <button
                type="submit"
                class="absolute right-2 top-2 btn btn-secondary btn-circle btn-sm"
                :disabled="!form.body && !form.attachment"
            >
                <SendIcon />
            </button>

            <!-- Attachment Preview -->
            <div v-if="attachmentPreview.value" class="indicator absolute left-2 bottom-12">
                <button
                    @click="$emit('remove-attachment')"
                    class="indicator-item badge badge-error w-6 h-6"
                >
                    <XIcon class="w-4 h-4" />
                </button>
                <img
                    :src="attachmentPreview.value"
                    alt="Attachment Preview"
                    class="object-cover w-24 h-24 border rounded overflow-hidden"
                />
            </div>
        </form>
    </div>
</template>

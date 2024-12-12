<template>
    <div class="z-10 w-full bg-base-200">
        <div class="fixed bottom-0 right-0 top-0 flex h-full w-full flex-col border-l border-neutral-200 md:w-[200px] lg:w-[250px] xl:w-[350px]">
            <!-- Header -->
            <div class="flex items-center border-b border-neutral-200 p-2 h-16">
                <div class="w-full flex justify-between items-center">
                    <div class="font-semibold">User Details</div>
                    <button @click="$emit('close')" class="btn btn-circle btn-secondary btn-sm">
                        <XIcon />
                    </button>
                </div>
            </div>

            <!-- User Info -->
            <div class="p-4 text-center">
                <img :src="user.profile_photo_url" :alt="user.name" class="w-24 h-24 rounded-full mx-auto" />
                <h3 class="font-semibold mt-2">{{ user.name }}</h3>
                <p class="text-sm text-gray-500">{{ user.email }}</p>
            </div>

            <!-- Shared Media -->
            <div class="flex-1 overflow-auto">
                <SectionDivider title="Shared Photos" />
                <div class="grid grid-cols-3 gap-2 p-2">
                    <div
                        v-for="attachment in attachments.data"
                        :key="attachment.id"
                        class="aspect-square relative group cursor-pointer"
                        @click="openImagePreview(attachment)"
                    >
                        <img
                            :src="attachment.attachment"
                            class="rounded object-cover w-full h-full"
                        />
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <ExpandIcon class="text-white" />
                        </div>
                    </div>
                </div>
                <div v-if="!attachments.data.length" class="text-center p-4">
                    <p class="text-gray-500">No shared photos yet...</p>
                </div>
            </div>
        </div>
    </div>
</template>

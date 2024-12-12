<template>
    <div class="flex justify-between items-center w-full bg-base-100 p-2">
        <div class="flex items-center space-x-2 h-12">
            <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full w-12 h-12" />
            <div>
                <p class="font-semibold">{{ user.name }}</p>
                <p class="text-xs" v-if="isOnline">Online</p>
            </div>
        </div>
        <div class="space-x-2">
            <button
                class="btn btn-circle btn-secondary btn-sm"
                @click="$emit('toggle-favorite')"
            >
                <StarIcon :class="{ 'text-yellow-400': isFavorite }" />
            </button>
            <button
                @click="$emit('toggle-info')"
                class="btn btn-circle btn-secondary btn-sm"
            >
                <InfoIcon />
            </button>
        </div>
    </div>
</template>

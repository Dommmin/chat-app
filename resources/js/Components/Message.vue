<template>
    <div
        class="flex items-end space-x-2"
        :class="message.from_id === $page.props.auth.user.id ? 'justify-end' : 'justify-start'"
    >
        <div
            class="text-white rounded-2xl px-4 py-2 max-w-[250px] lg:max-w-lg"
            :class="message.from_id === $page.props.auth.user.id ? 'bg-secondary' : 'bg-neutral/90'"
        >
            {{message.body}}
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    message: Object
});
</script>

<script setup>
import { Button } from '@/components/ui/button';
import { PanelLeft, PanelRight } from 'lucide-vue-next';

const props = defineProps({
    leftSidebarOpen: {
        type: Boolean,
        default: true
    },
    rightSidebarOpen: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['toggle-left-sidebar', 'toggle-right-sidebar']);

const toggleLeftSidebar = () => {
    emit('toggle-left-sidebar');
};

const toggleRightSidebar = () => {
    emit('toggle-right-sidebar');
};
</script>

<template>
    <header class="flex h-14 items-center gap-4 border-b border-border bg-background px-4">
        <Button
            variant="ghost"
            size="icon"
            class="h-8 w-8"
            @click="toggleLeftSidebar"
            :aria-label="leftSidebarOpen ? 'Hide left sidebar' : 'Show left sidebar'"
        >
            <PanelLeft class="h-4 w-4" />
        </Button>
        
        <!-- Header content slot -->
        <div class="flex items-center gap-2">
            <slot name="header-content"></slot>
        </div>
        
        <div class="ml-auto">
            <Button
                variant="ghost"
                size="icon"
                class="h-8 w-8"
                @click="toggleRightSidebar"
                :aria-label="rightSidebarOpen ? 'Hide right sidebar' : 'Show right sidebar'"
            >
                <PanelRight class="h-4 w-4" />
            </Button>
        </div>
    </header>
</template>
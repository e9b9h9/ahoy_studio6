<script setup>
// Simple right panel with basic HTML elements
</script>

<template>
    <div class="space-y-4">
        <div>
            <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Tools</h4>
            <div class="space-y-2">
                <p class="text-sm">🔧 Tool 1</p>
                <p class="text-sm">⚙️ Tool 2</p>
                <p class="text-sm">🛠️ Tool 3</p>
            </div>
        </div>
        
        <div>
            <h4 class="text-xs font-semibold text-muted-foreground uppercase mb-2">Status</h4>
            <div class="text-sm space-y-1">
                <p>Status: <span class="text-green-600">✅ Active</span></p>
                <p>Items: <span class="font-mono">42</span></p>
                <p>Mode: <span class="text-blue-600">Testing</span></p>
            </div>
        </div>
    </div>
</template>
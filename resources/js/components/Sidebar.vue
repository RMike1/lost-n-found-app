<script setup lang="ts">
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { ScrollArea } from "@/components/ui/scroll-area";

interface SidebarProps {
  categories : Array
}

const props = defineProps<SidebarProps>();

const emit = defineEmits([
  'filterByPostType','filterByCategory'
]);

const filterByPostType = (query) => {
  emit('filterByPostType',query)
} 
const filterByCategory = (query) => {

  emit('filterByCategory',query)
} 
</script>

<template>
  <div :class="cn('pb-12', $attrs.class ?? '')">
    <div class="space-y-4 py-4">
      <div class="px-3 py-2">
        <h2 class="mb-2 px-4 text-lg font-semibold tracking-tight">Items</h2>
        <div class="space-y-1">
        <Button @click="filterByPostType('found')" variant="secondary" class="w-full justify-start">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
              class="mr-2 h-4 w-4"
            >
              <rect width="7" height="7" x="3" y="3" rx="1" />
              <rect width="7" height="7" x="14" y="3" rx="1" />
              <rect width="7" height="7" x="14" y="14" rx="1" />
              <rect width="7" height="7" x="3" y="14" rx="1" />
            </svg>
            Found
          </Button>
          <Button @click="filterByPostType('lost')" variant="secondary" class="w-full justify-start">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
              class="mr-2 h-4 w-4"
            >
              <circle cx="12" cy="12" r="10" />
              <polygon points="10 8 16 12 10 16 10 8" />
            </svg>
            Lost
          </Button>
          
        </div>
      </div>
      <div class="py-2">
        <h2 class="relative px-7 text-lg font-semibold tracking-tight">Categories</h2>
        <ScrollArea class="h-[400px] px-1">
          <div class="space-y-1 p-2">
            <Button
              v-for="(category, i) in props.categories"
              :key="`${category}-${i}`"
              @click="filterByCategory(category.id)"
              variant="ghost"
              class="w-full justify-start font-normal"
            >
              <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
              class="mr-2 h-4 w-4"
            >
              <path d="m16 6 4 14" />
              <path d="M12 6v14" />
              <path d="M8 8v12" />
              <path d="M4 4v16" />
            </svg>
              {{ category.name }}
            </Button>
          </div>
        </ScrollArea>
      </div>
    </div>
  </div>
</template>

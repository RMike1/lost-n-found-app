<script setup lang="ts">
import { cn } from "@/lib/utils";
import { ContextMenu, ContextMenuTrigger } from "@/components/ui/context-menu";
import { SearchCheck  } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardFooter } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'

// import PlusCircledIcon from '~icons/radix-icons/plus-circled'

interface Item{
  title: string;
  post_type: string;
  item_images : any[];
  user: object
}

interface CardItemsList {
  aspectRatio?: "portrait" | "square";
  width?: number;
  height?: number;
  item: Item;
}

const props = withDefaults(defineProps<CardItemsList>(), {
  aspectRatio: "portrait",
});
</script>

<template>
  <div :class="cn('space-y-3', $attrs.class ?? '')">
    <Card>
      <CardContent class="grid gap-4">
        <ContextMenu>
          <ContextMenuTrigger>
            <div class="overflow-hidden rounded-md">
              <img
                :src="props.item.item_images[0]['image_url']"
                :alt="props.item.title"
                :width="width"
                :height="height"
                :class="
                  cn(
                    'h-auto w-auto object-cover transition-all hover:scale-105',
                    aspectRatio === 'portrait' ? 'aspect-[3/4]' : 'aspect-square'
                  )
                "
              />
            </div>
          </ContextMenuTrigger>
        </ContextMenu>
        <CardDescription>
          <div class="space-y-1 mt-3 text-sm">
            <div class="flex flex-cols-2 justify-between items-center mb-4">
              <h3 class="font-medium leading-none">
                {{ props.item.title }}
              </h3>
                <Badge :variant="props.item.post_type === 'found' ? 'outline' : 'destructive'">
                <span class="font-medium leading-none">
                  {{ props.item.post_type }}
                </span>
                </Badge>
            </div>
            <p class="text-xs font-medium leading-none mb-4">
              {{ item.post_type == "found" ? "Finder : " : "Reporter : " }}
              {{ props.item.user.name }}
            </p>
            <p class="text-xs font-medium leading-none mb-4">
              <span>Category : </span> {{ props.item.category.name }}
            </p>
            <p class="text-xs font-medium leading-none mb-4">
              <span>Location : </span> {{ props.item.village.name }}
            </p>
            <div class="flex items-center justify-between space-x-2">
              <Label for="is-approved">Approved ?</Label>
              <span> {{ props.item.is_approved ? 'Yes' : 'No' }}</span>
            </div>
          </div>
        </CardDescription>
      </CardContent>
      <CardFooter>
        <Button class="w-full"> <SearchCheck  class="h-4 w-4" /> Analyse </Button>
      </CardFooter>
    </Card>
  </div>
</template>

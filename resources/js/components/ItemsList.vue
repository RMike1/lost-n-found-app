<script setup lang="ts">
import { cn } from "@/lib/utils";
import { ContextMenu, ContextMenuTrigger } from "@/components/ui/context-menu";
import { Card, CardContent, CardDescription } from "@/components/ui/card";
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
      <CardContent>
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
            <div class="flex flex-cols-2 justify-between items-center">
              <h3 class="font-medium leading-none">
                {{ props.item.title }}
              </h3>
              <small>
                {{ props.item.post_type }}
              </small>
            </div>
            <p class="text-xs text-foreground">
              {{ item.post_type == 'found' ? 'Finder : ' : 'Reporter : '  }} {{ props.item.user.name  }} 
            </p>
            <p class="text-xs text-foreground">
             <span>Category : </span> {{ props.item.category.name  }} 
            </p>
            <p class="text-xs text-foreground">
             <span>Location : </span> {{ props.item.village.name  }} 
            </p>
              <div class="flex items-center space-x-2">
                <Label for="is-approved">Is Approved</Label>
                <Switch id="is-approved" />
              </div>
          </div>
        </CardDescription>
      </CardContent>
    </Card>
  </div>
</template>

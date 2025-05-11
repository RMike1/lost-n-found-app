<script setup lang="ts">
import { cn } from "@/lib/utils";
import { ContextMenu, ContextMenuTrigger } from "@/components/ui/context-menu";
import { Card, CardContent, CardDescription } from "@/components/ui/card";

// import PlusCircledIcon from '~icons/radix-icons/plus-circled'

interface CardItemsList {
  aspectRatio?: "portrait" | "square";
  width?: number;
  height?: number;
  item: object;
}

const props = withDefaults(defineProps<CardItemsList>(), {
  aspectRatio: "portrait",
});
</script>

<template>
  <div :class="cn('space-y-3', $attrs.class ?? '')">
    <Card>
      <!-- <CardHeader>
  <CardTitle>
  <h5 class="items-end text-sm">Lost</h5>
  </CardTitle>
  </CardHeader> -->
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
            <p class="text-xs text-muted-foreground">
              {{ props.item.user.name }}
            </p>
          </div>
        </CardDescription>
      </CardContent>
    </Card>
  </div>
</template>

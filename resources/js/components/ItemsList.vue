<script setup lang="ts">
import type { Album } from "@/composables/data/items";
import { cn } from "@/lib/utils";
import {
  ContextMenu,
  ContextMenuTrigger,
} from "@/components/ui/context-menu";

import { Card, CardHeader, CardTitle, CardContent, CardDescription } from "@/components/ui/card";

// import PlusCircledIcon from '~icons/radix-icons/plus-circled'
// import { playlists } from "@/composables/data/playlists";

interface AlbumArtworkProps {
  album: Album;
  aspectRatio?: "portrait" | "square";
  width?: number;
  height?: number;
}
withDefaults(defineProps<AlbumArtworkProps>(), {
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
            :src="album.cover"
            :alt="album.name"
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
        {{ album.name }}
      </h3>
      <small>
      Lost
      </small>
    
    </div>
      <p class="text-xs text-muted-foreground">
        {{ album.artist }}
      </p>
    </div>
    </CardDescription>
    </CardContent>
  </Card>
  </div>
</template>

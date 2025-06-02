<script setup lang="ts">
import { cn } from "@/lib/utils";
import { ContextMenu, ContextMenuTrigger } from "@/components/ui/context-menu";
import { createReusableTemplate, useMediaQuery } from '@vueuse/core'
import { ref } from 'vue'
import { EyeOff, SearchCheck } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardFooter } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input'


const [UseTemplate, GridForm] = createReusableTemplate()
const isDesktop = useMediaQuery('(min-width: 768px)')

const isOpen = ref(false)

import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'

import {
  Form,
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'

interface Item {
  title: string;
  post_type: string;
  item_images: Array<{ image_url: string }>;
  user: {
    name: string;
  };
  category: {
    name: string;
  };
  village: {
    name: string;
  };
  is_approved: boolean;
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
    <Form v-slot="{ handleSubmit }" as="" keep-values>
      <Dialog>
        <Card>
          <CardContent class="grid gap-4">
            <ContextMenu>
              <ContextMenuTrigger>
                <div class="overflow-hidden rounded-md">
                  <img :src="props.item.item_images[0]['image_url']" :alt="props.item.title" :width="width"
                    :height="height" :class="cn(
                      'h-auto w-auto object-cover transition-all hover:scale-105',
                      aspectRatio === 'portrait' ? 'aspect-[3/4]' : 'aspect-square'
                    )
                      " />
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
            <DialogTrigger as-child>
              <Button class="w-full">
                <SearchCheck class="h-4 w-4" /> Review
              </Button>
            </DialogTrigger>
          </CardFooter>
        </Card>

        <DialogContent class="sm:max-w-[1200px] grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh]">
          <DialogHeader class="p-6 pb-0">
            <DialogTitle>Review</DialogTitle>
            <DialogDescription>
              ipsum dolor sit amet consectetur adipisicing elit. Quasi, cumque. Lorem ipsum dolor sit amet consectetur
            </DialogDescription>
          </DialogHeader>
          <!-- <div class="grid grid-cols-2 gap-4 py-4 overflow-y-auto px-6"> -->
          <!-- <Card>
                <CardContent class="flex flex-col justify-between gap-4"> -->
          <ContextMenu class="grid grid-cols-2 gap-4 py-4 overflow-y-auto px-6">
            <ContextMenuTrigger>
              <div class="overflow-hidden rounded-md">
                <img :src="props.item.item_images[0]['image_url']" :alt="props.item.title" :width="width"
                  :height="height" :class="cn(
                    'h-auto w-auto object-cover transition-all hover:scale-105',
                    aspectRatio === 'portrait' ? 'aspect-[3/4]' : 'aspect-square'
                  )
                    " />
              </div>
              <Button variant="outline" class="w-full" @click="isOpen = true">
                <EyeOff class="h-4 w-4" /> Hide Sensitive info
              </Button>
            </ContextMenuTrigger>
            <ContextMenuContent>
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
            </ContextMenuContent>
          </ContextMenu>
          <!-- </CardContent> -->
          <!-- </Card> -->

          <!-- <form id="dialogForm" @submit.prevent>
                <FormField v-slot="{ componentField }" name="username">
                  <FormItem>
                    <FormLabel>Username</FormLabel>
                    <FormControl>
                      <Input type="text" placeholder="shadcn" v-bind="componentField" />
                    </FormControl>
                    <FormDescription>
                      This is your public display name.
                    </FormDescription>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </form> -->
          <!-- </div> -->

          <DialogFooter class="p-6 pt-0">
            <DialogClose as-child>
              <Button type="button" variant="secondary">
                Close
              </Button>
            </DialogClose>
            <Button type="submit">
              Save changes
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

    </Form>
  </div>
</template>

<script setup lang="ts">
import { ScrollArea } from '@/components/ui/scroll-area'
import { Separator } from '@/components/ui/separator'
import {Tabs,TabsContent,TabsList,TabsTrigger} from '@/components/ui/tabs'
// import {Select} from '@/components/ui/select'
import { Input } from '@/components/ui/input'
import { Search } from 'lucide-vue-next'

// import PlusCircledIcon from '~icons/radix-icons/plus-circled'
import ItemsList from '@/components/ItemsList.vue'
import ItemEmptyPlaceholder from '@/components/ItemPlaceholder.vue';

import Sidebar from '@/components/Sidebar.vue'

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem} from '@/types';


interface Props {
    // mustVerifyEmail: boolean;
    status?: string;
    categories : object;
    items : object;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Items',
        href: '/items',
    },
    {
        title: 'All Items',
        href: '/items',
    },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Lost-Items" />
    <!-- <Menu /> -->
    <div class="border-t">
      <div class="bg-background">
        <div class="grid lg:grid-cols-5">
          <Sidebar :categories="categories" class="hidden lg:block" />
          <div class="col-span-3 lg:col-span-4 lg:border-l">
            <div class="h-full px-4 py-6 lg:px-8">
              <Tabs default-value="all" class="h-full space-y-6">
                <div class="space-between flex items-center justify-between">
                  <TabsList>
                    <TabsTrigger value="all" class="relative cursor-pointer">
                      All
                    </TabsTrigger>
                    <TabsTrigger value="lost" class="cursor-pointer"> Lost </TabsTrigger>
                    <TabsTrigger value="found" class="cursor-pointer">
                      Found
                    </TabsTrigger>
                  </TabsList>
                  <div class="lg:hidden">
                  
                  </div>
                  <div class="hidden lg:block">
                    <div class="ml-auto mr-4 relative">
                      <Input
                        id="search"
                        type="text"
                        placeholder="Search..."
                        class="pl-10"
                      />
                      <span
                        class="absolute start-0 inset-y-0 flex items-center justify-center px-2"
                      >
                        <Search class="size-6 text-muted-foreground" />
                      </span>
                    </div>
                  </div>
                </div>
                <TabsContent value="all" class="border-none p-0 outline-none">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">All Items</h2>
                      <p class="text-sm text-muted-foreground">
                        Including Lost and Found Items.
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <div class="relative">
                    <ScrollArea>
                      <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 space-x-2">
                        <ItemsList
                          v-for="item in items"
                          :key="item.id"
                          :item="item"
                          class="w-[150px] lg:w-[250px]"
                          aspect-ratio="square"
                          :width="250"
                          :height="330"
                        />
                      </div>
                    </ScrollArea>
                  </div>
                </TabsContent>
                <TabsContent
                  value="lost"
                  class="h-full flex-col border-none p-0 data-[state=active]:flex"
                >
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h2 class="text-2xl font-semibold tracking-tight">New Episodes</h2>
                      <p class="text-sm text-muted-foreground">
                        Your favorite podcasts. Updated daily.
                      </p>
                    </div>
                  </div>
                  <Separator class="my-4" />
                  <ItemEmptyPlaceholder />
                </TabsContent>
              </Tabs>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

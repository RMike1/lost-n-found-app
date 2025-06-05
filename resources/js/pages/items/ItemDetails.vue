<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ArrowLeft } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { formatDistanceToNow } from 'date-fns';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardFooter } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import VueMagnifier from '@websitebeaver/vue-magnifier';
import '@websitebeaver/vue-magnifier/styles.css';

interface Props {
    item: {
        id: string;
        title: string;
        post_type: string;
        item_images: Array<{ image_url: string, id: string, title: string }>;
        user: {
            name: string;
        };
        category: {
            name: string;
        };
        district: {
            name: string;
        };
        sector: {
            name: string;
        };
        cell: {
            name: string;
        };
        village: {
            name: string;
        };
        is_approved: boolean;
        is_resolved: string;
        description: string;
        created_at: string;
    };
    previousUrl?: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Items',
        href: '/items',
    },
    {
        title: props.item.title,
        href: '#',
    }
];

// const goBack = () => {
//     if (props.previousUrl) {
//         router.get(props.previousUrl);
//     } else {
//         router.get('/items');
//     }
// };
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head :title="item.title" />    

        <div class="container mx-auto px-4 py-6 w-3/5">
            <Card>
                <Link :href="route('items.all')" class="inline-flex mb-6">
                <Button variant="ghost" class="gap-2">
                    <ArrowLeft class="h-4 w-4" />
                    Back
                </Button>
                </Link>
                <CardHeader class="items-center justify-center">
                    <h1 class="text-3xl font-bold">{{ item.title }}</h1>
                </CardHeader>
                <CardContent>
                    <div class="flex gap-8">
                        <div class="w-1/4">
                            <VueMagnifier v-for="itemImage in item.item_images" :key="itemImage.id"
                                :src="itemImage.image_url" :alt="itemImage.title" class="rounded-lg object-cover mb-4"
                                width=100% />
                        </div>

                        <div class="w-3/4">
                            <div class="flex items-start justify-between">
                                <dt class="font-medium text-muted-foreground">Status</dt>
                                <Badge :variant="item.post_type === 'found' ? 'outline' : 'destructive'">
                                    {{ item.post_type }}
                                </Badge>
                            </div>

                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="font-medium text-muted-foreground">
                                        {{ item.post_type === 'found' ? 'Finder' : 'Reporter' }}
                                    </dt>
                                    <dd>{{ item.user.name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-muted-foreground">Category</dt>
                                    <dd>{{ item.category.name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-muted-foreground">Location</dt>
                                    <dd>{{ item.district.name }}, {{ item.sector.name }}, {{ item.cell.name }},{{
                                        item.village.name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-muted-foreground">Approval</dt>
                                    <dd>{{ item.is_approved ? 'Approved' : 'Pending' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-muted-foreground">Resolved?</dt>
                                    <dd>{{ item.is_resolved ? 'Resolved' : 'UnResolved' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-muted-foreground">Posted At</dt>
                                    <dd> {{ formatDistanceToNow(new Date(item.created_at), { addSuffix: true }) }}</dd>
                                </div>
                                <div class="grid grid-full gap-4">
                                    <div>
                                        <dt class="font-medium text-muted-foreground mb-2">Description</dt>
                                        <dd>{{ item.description }}</dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <!-- <div class="grid md:grid-cols-2 gap-8"> -->
        </div>
    </AppLayout>
</template>
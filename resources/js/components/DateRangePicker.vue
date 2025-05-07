<script setup lang="ts">
import type { DateRange } from 'reka-ui'
import { cn } from '@/lib/utils'
import  Button  from '@/components/ui/button/Button.vue'
import { RangeCalendar }  from '@/components/ui/range-calendar'
import {Popover,PopoverContent,PopoverTrigger} from '@/components/ui/popover'
import { CalendarDate, DateFormatter, getLocalTimeZone, now } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import { ref, watch  } from 'vue'

const props = defineProps<{
    date_range: Object
}>();


const df = new DateFormatter('en-US', {
  dateStyle: 'medium',
})

const today = now(getLocalTimeZone())

const startOfMonth = new CalendarDate(today.year, today.month, 1)
const endOfMonth = startOfMonth.add({ months: 1 }).subtract({ days: 1 })

const value = ref({
  start: startOfMonth,
  end: endOfMonth,
}) as Ref<DateRange>

const emit = defineEmits<{
  (e: 'update', value: DateRange): void
}>()

watch(value, (newValue) => {
  emit('update', newValue)
})

</script>

<template>
  <div :class="cn('grid gap-2', $attrs.class ?? '')">
    <Popover>
      <PopoverTrigger as-child>
        <Button
          id="date"
          :variant="'outline'"
          :class="cn(
            'w-full sm:w-[300px] justify-start text-left font-normal',
            !value && 'text-muted-foreground',
          )"
        >
          <CalendarIcon class="mr-2 h-4 w-4" />

          <template v-if="value.start">
            <template v-if="value.end">
              {{ df.format(value.start.toDate(getLocalTimeZone())) }} - {{ df.format(value.end.toDate(getLocalTimeZone())) }}
            </template>

            <template v-else>
              {{ df.format(value.start.toDate(getLocalTimeZone())) }}
            </template>
          </template>
          <template v-else>
            Pick a date
          </template>
        </Button>
      </PopoverTrigger>
      <PopoverContent class="w-auto p-0" align="end">
        <RangeCalendar
          v-model="value"
          weekday-format="short"
          :number-of-months="2"
          initial-focus
          :placeholder="value.start"
          @update:start-value="(startDate) => value.start = startDate"
        />
      </PopoverContent>
    </Popover>
  </div>
</template>
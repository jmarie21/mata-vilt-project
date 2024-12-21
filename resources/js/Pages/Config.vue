<script>
import Button from "primevue/button";

export default {
    props: {
        scheduleInterval: String, // Get the current interval from the backend
    },
    data() {
        return {
            interval: this.scheduleInterval, // Initialize the interval with the value from backend
        };
    },
    methods: {
        submitForm() {
            this.$inertia.post("/config", { interval: this.interval });
        },
    },
};
</script>

<template>
    <div>
        <h1>Task Scheduler Configuration</h1>

        <!-- Show success message if available -->
        <div v-if="$page.props.flash" class="alert alert-success">
            {{ $page.props.flash }}
        </div>

        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="interval">Select Scheduler Interval:</label>
                <select v-model="interval" id="interval" class="form-control">
                    <option
                        value="everyMinute"
                        :selected="interval === 'everyMinute'"
                    >
                        Every Minute
                    </option>
                    <option
                        value="everyFiveMinutes"
                        :selected="interval === 'everyFiveMinutes'"
                    >
                        Every 5 Minutes
                    </option>
                    <option value="hourly" :selected="interval === 'hourly'">
                        Hourly
                    </option>
                    <option value="daily" :selected="interval === 'daily'">
                        Daily
                    </option>
                </select>
            </div>
            <Button type="submit" class="btn btn-primary mt-3">
                Save Interval
            </Button>
        </form>
    </div>
</template>

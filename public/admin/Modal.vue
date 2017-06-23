<template>
    <div v-show="shown">
        <div class="backdrop" @click.stop="close"></div>
        <div class="modal" :id="name">
            <header>
                <slot name="header"></slot>
                <i class="fa fa-times close" @click.stop="close"></i>
            </header>
            <slot name="body"></slot>
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<style scoped lang="scss">
    @import "../sass/_color-variables.scss";

    .modal > * {
        padding: 2rem;
    }
    .modal > header {
        border-bottom: 1px solid $platinum;
        padding-right: 5rem;
        .close {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-size: 1rem;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 0;
        }
    }
</style>

<script>
    /**
     * @author Lukáš Krotovič
     * @version 1.0.0
     * @package Topazz
     */
    export default {
        props: {
            name: {
                type: String,
                required: true
            }, shown: {
                type: Boolean,
                required: true
            }
        }, data() {
            return {};
        }, methods: {
            close() {
                this.$emit('close');
            }
        }
    }
</script>
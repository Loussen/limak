<template>
    <select style="width:100%;" class="form-control" multiple>
        <slot></slot>
    </select>
</template>

<script>
    import "select2/dist/css/select2.min.css"
    export default {
        props: {
            "placeholder": {
                type: String,
                default: "Seçim edin"
            },
            "value": {
                type: Array|Number|String
            },
            "options": {
                type: Array
            },
            "isMultiple": {
                type: Boolean,
                default: false
            },
            "url": {
                type: String,
            },
            "query": {
                type: Object
            },
            "parser": {
                type: String
            },
            "parent": {
                type: String
            }
        },
        mounted() {
            var vm = this,
                element = $(this.$el);
            console.log(element);
            const ajax = (vm.url && ({
                url: function () {
                    return vm.url
                },
                data(params) {
                    return {
                        search: params.term,
                        page: params.page || 0,
                        perPage: params.perPage || 10
                    };
                },
                processResults(data, params) {
                    params.page = params.page || 0;
                    let {data: {items, totalCount}} = data;
                    items.map(function (node) {
                        node.text = (vm.parser && eval(vm.parser)) || node.name;
                    });
                    return {
                        results: items,
                        pagination: {
                            more: (params.page * params.perPage) < totalCount
                        }
                    };
                },
            })) || null;

            const configs = {
                ajax,
                multiple: vm.isMultiple,
                width: "100%",
                placeholder: vm.placeholder,
                data: vm.options,
                dropdownParent: $(vm.parent)
            };
            //TODO: Burada dropdownParent olduqda child componentlerde dropdown alt hissesi gorsenmir yuxarda acilir
            if (!vm.parent) {
                delete configs.dropdownParent;
            }
            element.select2(configs);


            element.on("change", function (node) {
                vm.$emit("input", $(this).val());
            });

        },
        watch: {
            value: function (value) {
                JSON.stringify($(this.$el).val() ) == JSON.stringify(value) || $(this.$el).val(value).trigger("change")
            },
        }
    }
</script>

<style>
</style>
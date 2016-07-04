'use strict';

angular.module('eveTool')
    .controller('corpOverviewController', ['$scope', '$http', 'corporationDataManager', 'selectedCorpManager', function($scope, $http, corporationDataManager, selectedCorpManager){
        var getMemberTransactionDistribution = function(member){
            var trans = member.orig_ids;
            var res = _.groupBy(trans, 'ref_type.ref_type_id');

            return Object.keys(res).map(function(key){
                return res[key];
            });
        };

        var sumTotals = function (list){
            angular.forEach(list, function(ref, i){
                var sum = _.reduce(_.pluck(ref.trans, 'amount'), function(init, carry){
                    return init + carry;
                });

                list[i]['total'] = sum;
            });

            return list;

        };

        function resetParams (){
            $scope.buy_orders = [];
            $scope.journal_transactions = [];
            $scope.sell_orders = [];
            $scope.members = [];
            $scope.ref_types = [];
        }

        /**
         * Begin D3 Graph
         */
        function updateSVG(){
            $('svg').remove();

            $scope.svg_start_date = $scope.current_date;
            var margins = {
                top: 10,
                right: 40,
                bottom: 15,
                left: 115
            };

            var height = 100 - margins.top ;
            var width = $('.graphs')[0].clientWidth - margins.right;

            var color = d3.scale.category10();

            var xScale = d3.time.scale().range([0,  width - margins.left]);
            var yScale = d3.scale.linear().range([ height, 0]);

            var xAxis = d3.svg.axis()
                .scale(xScale)
                .ticks(d3.time.hour, 12)
                .tickSize(-height)
                .orient("bottom");

            var area = d3.svg.area()
                .interpolate("basis")
                .x(function (d) { return xScale(d.date); })
                .y0(function (d) { return yScale(d.y0); })
                .y1(function (d) { return yScale(d.y0 + d.y); });

            var stack = d3.layout.stack()
                .values(function(d){ return d.values; });

            var parse = d3.time.format("%Y-%m-%dT%H:%M:%LZ").parse;

            var vis = d3.select('.graphs').append('svg')
                .attr('width', "100%")
                .attr('height', height+margins.top+margins.bottom)
                .append("g")
                .attr("transform", "translate("+ margins.bottom+"," + margins.top +")");

            $http.get(Routing.generate('api.corporation.account_data', { id: $scope.selected_corp.id , date: moment($scope.current_date).format('X') })).then(function(data){
                if (data.length > 10){
                    // Nest stock values by symbol.
                    var wallets  = d3.nest()
                        .key(function(d) { return d.name; })
                        .entries(data);

                    var cDomain = [];

                    var maxTotal = 0;
                    wallets.forEach(function(w) {
                        w.values.forEach(function(d) { d.date = parse(d.date); d.balance = +d.balance; });

                        maxTotal += d3.max(w.values, function(d) { return d.balance; });
                        cDomain.push(w.key);

                        w.values.sort(function(a,b){
                            return a.date - b.date;
                        });

                    });

                    var yAxis = d3.svg.axis()
                        .scale(yScale)
                        .tickSize(-width)
                        .ticks((maxTotal / 100000000) / 3)
                        .tickFormat(d3.format('$s'))
                        .orient("right");

                    color.domain(cDomain);

                    var wStack = stack(color.domain().map(function(name){
                        return {
                            name: name,
                            values: _.find(wallets, function(w){ return w.key == name; }).values.map(function(d){
                                return {
                                    date: d.date,
                                    y: d.balance
                                };
                            })
                        };
                    }));

                    xScale.domain(d3.extent(data, function(d){
                        return d.date;
                    }));


                    yScale.domain(d3.extent([0, maxTotal], function(d){
                        return d;
                    }));

                    var svgWallets = vis.selectAll('.wallet')
                        .data(wStack)
                        .enter().append("g")
                        .attr("class", "wallet");

                    svgWallets.append("path")
                        .attr("class", "area")
                        .attr("d", function(d){ return area(d.values); })
                        .style("fill", function(d){ return color(d.name); });

                    vis.append("g")
                        .attr("class", "x-axis")
                        .attr("transform", "translate(0,"+height+")")
                        .call(xAxis);

                    vis.append("g")
                        .attr("class", "x-axis")
                        .call(yAxis);

                    vis.append("circle");

                    var legend = vis.selectAll(".legend")
                        .data(color.domain().slice().reverse())
                        .enter().append("g")
                        .attr("class", "legend")
                        .attr("transform", function (d, i) {
                            return "translate(0," + i * 15 + ")";
                        });

                    legend.append("rect")
                        .attr("x", width - 18)
                        .attr("width", 10)
                        .attr("height", 10)
                        .style("fill", color);

                    legend.append("text")
                        .attr("x", width - 24)
                        .attr("y", 9)
                        .attr("dy", ".35em")
                        .style("text-anchor", "end")
                        .text(function (d) {
                            return d;
                        });
                } else {
                    $scope.not_enough_data = true;
                }
            });
        }

        /**
         * End D3
         */
        
        $scope.selected_account = null;
        $scope.selected = 0;
        $scope.buy_orders = [];
        $scope.members = [];
        $scope.ref_types = [];
        $scope.journal_transactions = [];
        $scope.totalBalance = 0;
        $scope.grossProfit = 0;
        $scope.sell_orders = [];
        $scope.image_width = 28;
        $scope.loading = false;
        $scope.page = 'stats';
        $scope.current_date = moment().format('MM/DD/YY');
        $scope.orig_date = $scope.current_date;

        $scope.$watch('accounts', function(val){
            if (typeof val !== 'undefined' && val.length > 0){
                $scope.selectAccount($scope.accounts[0]);
            }
        });

        $scope.$watch('selected_account', function(val){
            if (typeof val !== null) {
                if ($scope.page !== 'stats'){
                    $scope.switchPage($scope.page);
                }
            }
        });

        $scope.showView = function(view){
            $scope.selected = view;
        };

        $scope.$watch(function(){ return selectedCorpManager.get(); }, function(val){
            if (typeof val.id === 'undefined'){
                return;
            }

            $scope.loading = true;
            $scope.selected_corp = val;

            $scope.totalBalance = 0;
            $scope.grossProfit = 0;

            resetParams();

            corporationDataManager.getLastUpdate(val, 1).then(function(data){
                if (data !== null){
                    $scope.updated_at = moment(data.created_at).format('x');
                    $scope.update_succeeded = data.succeeded;
                    $scope.next_update = moment(data.created_at).add(10, 'minutes').format('x');
                }
            });

            corporationDataManager.getAccounts(val, $scope.current_date).then(function(data){
                $scope.accounts = data;

                var total = 0;
                var lastDay = 0;
                angular.forEach($scope.accounts, function(a){
                    total += parseFloat(a.current_balance);
                    lastDay += parseFloat(a.last_day_balance);
                });

                $scope.totalBalance = total;
                $scope.percentChangeBalance = { percent: ((total - lastDay) / lastDay) * 100, diff: total - lastDay }
            }).then(function(){
                updateSVG();
                $scope.loading = false;
            });
        });

        $scope.sumTrans = function(trans){
            return _.reduce(_.pluck(trans, 'amount'), function(init, carry){
                return init + carry;
            });
        };

        $scope.switchPage = function(page){
            var date = moment($scope.current_date).format('X');
            $scope.loading = true;
            if ($scope.selected_account !== null ){
                var ret = (function(date){
                    switch (page) {
                        case 'buy':
                            return corporationDataManager.getMarketTransactions($scope.selected_corp, $scope.selected_account, date, 'buy').then(function (data) {
                                $scope.buy_orders = data;
                            });
                        case 'sell':
                            return corporationDataManager.getMarketTransactions($scope.selected_corp, $scope.selected_account, date, 'sell').then(function (data) {
                                $scope.sell_orders = data;
                            });
                        case 'journal':
                            return corporationDataManager.getJournalTransactions($scope.selected_corp, $scope.selected_account, date).then(function (data) {
                                $scope.journal_transactions = data;

                            });
                        case 'stats':
                            return corporationDataManager.getJournalTypeAggregate($scope.selected_corp, date).then(function(data){
                                $scope.ref_types = data;
                                $scope.segments = $scope.getSegments($scope.ref_types, ($scope.ref_types.length / 2) + 1 );

                            }).then(function(){
                                corporationDataManager.getJournalUserAggregate($scope.selected_corp,date).then(function(data) {
                                    $scope.members = data;
                                    angular.forEach($scope.members, function(m, i){
                                        var dist = getMemberTransactionDistribution(m);
                                        $scope.members[i].distribution = dist;
                                    });

                                    $scope.member_segments = $scope.getSegments($scope.members, ($scope.members.length / 2) + 1 );
                                });
                            });

                    }

                    return { then: function(func) { return func(); }};
                })(date);

                ret.then(function(){
                    $scope.loading = false;
                    $scope.page = page;
                });

            }
        };

        $scope.today = function(){
            $scope.current_date = $scope.orig_date;

            if ($scope.page.length){
                $scope.switchPage($scope.page);
            }
            updateSVG();
        };

        $scope.back = function(){
            $scope.current_date = moment($scope.current_date).subtract(1,'day').format('MM/DD/YY');
            resetParams();

            var start = moment($scope.svg_start_date);

            if ($scope.page.length){
                $scope.switchPage($scope.page);
            }

            if (start.diff($scope.current_date, 'days') == 5){
                updateSVG();
            }

        };

        $scope.forward = function(){
            $scope.current_date = moment($scope.current_date).add(1,'day').format('MM/DD/YY');

            if ($scope.page.length){
                $scope.switchPage($scope.page);
            }

            updateSVG();

        };

        $scope.byWeek = function(){

        };
        $scope.byMonth = function(){

        };

        $scope.byQuarter = function(){

        };
        $scope.selectAccount = function(acc){

            if ($scope.selected_account === null
                || $scope.selected_account.id !== acc.id){
                $scope.loading = true;
                resetParams();
                $scope.selected_account = acc;

                $scope.switchPage($scope.page);

            }
        };

        $scope.sumOrders = function(orders){
            var sum = 0;

            angular.forEach(orders, function(o){
                sum+= o.price * o.quantity;
            });

            return sum;
        };

        $scope.getJournalDifference = function(){
            if (typeof $scope.journal_transactions !== 'undefined' && $scope.journal_transactions.length){
                var sorted = _.sortBy($scope.journal_transactions, 'date');

                return parseFloat(sorted[sorted.length-1].balance) - parseFloat(sorted[0].balance);
            }

            return 0;
        };

        $scope.findGross = function(){
            if (typeof $scope.buy_orders !== 'undefined' && $scope.buy_orders.length > 0
                && typeof $scope.sell_orders !== 'undefined' && $scope.sell_orders.length > 0){

                var buy = $scope.sumOrders($scope.buy_orders);
                var sell = $scope.sumOrders($scope.sell_orders);

                return sell - buy;
            }

            return 0;
        };

        $scope.sumTransactions = function(){
            var trans = _.pluck($scope.ref_types, 'trans');
            var total = 0;

            angular.forEach(trans, function(t){
                total += parseFloat(t[0].total_amount);
            });
            return total;
        };

        $scope.getSegments = function(list, size){
            return _.chunk(list, size);
        };


    }]);

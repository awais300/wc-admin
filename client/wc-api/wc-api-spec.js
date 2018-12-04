/** @format */

/**
 * Internal dependencies
 */
import orders from './orders';
import reportStats from './reports/stats';

function createWcApiSpec() {
	return {
		selectors: {
			...orders.selectors,
			...reportStats.selectors,
		},
		operations: {
			read( resourceNames ) {
				return [
					...orders.operations.read( resourceNames ),
					...reportStats.operations.read( resourceNames ),
				];
			},
		},
	};
}

export default createWcApiSpec();

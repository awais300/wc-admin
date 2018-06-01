/** @format */
/**
 * External dependencies
 */
// import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import { cloneElement, Component } from '@wordpress/element';
import { Dashicon } from '@wordpress/components';
import PropTypes from 'prop-types';

/**
 * Internal dependencies
 */
import './style.scss';
import { EllipsisMenu } from '../ellipsis-menu';

// @TODO Use @wordpress/date to format the date

class ActivityCard extends Component {
	render() {
		const { actions, className, date, icon, image, label, menu, children } = this.props;
		const cardClassName = classnames( 'woocommerce-activity-card', className );

		return (
			<section className={ cardClassName }>
				<header className="woocommerce-activity-card__header">
					<span className="woocommerce-activity-card__icon">{ icon }</span>
					<h3 className="woocommerce-activity-card__label">
						{ label }
						{ date && <span className="woocommerce-activity-card__date">– { date }</span> }
					</h3>
					{ menu && <div className="woocommerce-activity-card__menu">{ menu }</div> }
				</header>
				<div className="woocommerce-activity-card__body">
					<div className="woocommerce-activity-card__content">{ children }</div>
					{ image && <div className="woocommerce-activity-card__image">{ image }</div> }
				</div>
				{ actions && (
					<footer className="woocommerce-activity-card__actions">
						{ actions.map( ( item, i ) => cloneElement( item, { key: i } ) ) }
					</footer>
				) }
			</section>
		);
	}
}

ActivityCard.propTypes = {
	actions: PropTypes.oneOfType( [ PropTypes.array, PropTypes.element ] ),
	className: PropTypes.string,
	children: PropTypes.node.isRequired,
	date: PropTypes.string,
	icon: PropTypes.node,
	image: PropTypes.node,
	label: PropTypes.string.isRequired,
	menu: PropTypes.shape( {
		type: PropTypes.oneOf( [ EllipsisMenu ] ),
	} ),
};

ActivityCard.defaultProps = {
	icon: <Dashicon icon="warning" />,
};

export default ActivityCard;
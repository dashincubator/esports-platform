/**
 *------------------------------------------------------------------------------
 *
 *  Activate Matchfinder Gametype Accordion On Select Change
 *
 */

resolve(['directive', 'dom', 'node', 'state'], function(directive, dom, node, state) {

    'use strict';


    let ids = {
            gametype: 'modal-match-accept-gametype',
            id: 'modal-match-accept-id',
            playersPerTeam: 'modal-match-accept-playersPerTeam'
        };


    const accept = function(e) {
        let json = JSON.parse(this.dataset.json);

        dom.update(() => {
            dom.id(ids.id).value = json.id;

            node.text(dom.id(ids.gametype), json.gametype);
            node.text(dom.id(ids.playersPerTeam), json.playersPerTeam);
        });
    };

    const gametype = function(e) {
        let gametype = e.target.value,
            trigger = this;

        if (!gametype) {
            return;
        }

        let accordion = `matchfinder-gametype-${gametype}`;

        if (trigger.dataset.accordion == accordion) {
            return;
        }

        node.update(trigger, {
            attribute: {
                'data-accordion': accordion
            },

            after: () => {
                // Activate Gametype Accordion
                directive.dispatch('accordion', {}, trigger);

                // Activate Roster Options
                let target = dom.id(`matchfinder-roster-${gametype}`);

                if (!target) {
                    return;
                }

                state.deactivate(node.siblings(target));
                state.activate(target);
            }
        });
    };


    directive.on('matchfinder-accept', accept);
    directive.on('matchfinder-gametype', gametype);

});

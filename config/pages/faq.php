<?php

return [
    "categories" => [
        [
            "title" => "Bank",
            "sections" => [
                [
                    "title" => "How do I add money to my bank?",
                    "content" => [
                        "Click on your profile avatar (top right corner), and a menu with bank balance will pop up. Click the red + button."
                    ]
                ],
                [
                    "title" => "How do I withdraw money from my bank?",
                    "content" => [
                        "Click on your profile avatar (top right corner), and a menu with bank balance will pop up. Click the black - button."
                    ]
                ],
                [
                    "title" => "I've won $600, now my bank is locked. How do I unlock?",
                    "content" => [
                        "Congrats! Now you've got the IRS to deal with. We need to collect some information from you first before you can withdraw more than $600 in any given year.
                        We'll email you automatically when this happens, but if we don't - shoot an email over to admin@gamrs.net"
                    ]
                ],
                [
                    "title" => "Transaction holds",
                    "content" => [
                        "If a pending transaction is flagged, put on hold, reversed, voided, etc. Paypal will notify Gamrs.  Gamrs will temporarily place a lock on the
                        user's account. This will enable access to only the ticket support center.  Please open a support ticket and resolve the held transaction."
                    ]
                ],
                [
                    "title" => "When are event entry fees deducted from my bank balance?",
                    "content" => [
                        "For leagues, when the team admin locks the roster - all players will have the entry fee deducted from their balance.
                        For tournaments, when a member of the team checks the team in."
                    ]
                ]
            ]
        ],
        [
            "title" => "Ladders",
            "sections" => [
                [
                    "title" => "What's the difference between a league and a ladder?",
                    "content" => [
                        "Leagues are premium only ladders really.
                        Our free ladders usually reward it's top finishers with free premium memberships.
                        Our premium leagues - each league with its own ladder - reward its top finishers with tons of cash."
                    ]
                ],
                [
                    "title" => "Are ladder rankings different than game rankings?",
                    "content" => [
                        "Yes - the ranking in the ladder is unique to that ladder.
                        Your overall ranking for a game is based off every match you play in that game, despite what particular competition the match was hosted under."
                    ]
                ],
                [
                    "title" => "Do ladder matches affect my overall game rank?",
                    "content" => [
                        "Yes - both individual and team ladder matches will have an affect on your overall game rank."
                    ]
                ],
                [
                    "title" => "What is the difference between free and paid ladders?",
                    "content" => [
                        "Paid ladders are usually reserved for our Premium members only.
                        Free ladders are open to everyone."
                    ]
                ]
            ]
        ],
        [
            "title" => "Leagues",
            "sections" => [
                [
                    "title" => "What is a league?",
                    "content" => [
                        "Leagues are premium ladders open exclusively for our Premium gamrs.
                        They feature incredible prize pools, top-notch support, and a stop-loss feature unique to Gamrs.
                        After [X] number of losses (defined in league rules), a team will be deemed ineligible.
                        They will no longer be able to play any new matches.  This creates a unique aspect to the league, rewarding wins over playing tons of matches."
                    ]
                ],
                [
                    "title" => "How often are our leagues?",
                    "content" => [
                        "We host a wide variety of leagues - most running concurrently on either a weekly, bi-weekly, monthly, or seasonal schedule.
                        Due to our stop-loss feature, we provide plenty of other leages to compete in after you hit your [X] loss limit."
                    ]
                ],
                [
                    "title" => "How does the prize pool work?",
                    "content" => [
                        "Free leagues post a prize pool that remains the same throughout the league.
                        Paid entry leagues post dynamic prize pools.
                        Every paid entry fee adds to the pool.
                        The more that enter, the greater the prize.
                        Winnings are paid out 24 hours after the league end date - giving our admin team time to screen out any cheaters."
                    ]
                ],
                [
                    "title" => "What is your league roster lock system?",
                    "content" => [
                        "Teams will not be able to play any league matches until they lock their team roster.
                        We do not allow any changes to your roster once you declare your team ready to compete.
                        This prevents team admins from abusing the system, removing or adding players at the end of the league.
                        Our team ladders usually allow 3v3, 4v4, and 5v5 matches.
                        You can lock your roster of 5 players, and select 3 for a 3v3 match.
                        We believe this provides enough flexibility for alternates over the course of the league."
                    ]
                ],
                [
                    "title" => "How does the ranking system work?",
                    "content" => [
                        "Each team competing a league is ranked based on the Glicko2 ranking system.
                        Wins increase your rank score, losses decrease it.
                        A win against a higher rank team will earn more reward points than a win against a low ranking team.
                        For more about our ranking system, check out the 'Rating System' FAQ section."
                    ]
                ]
            ]
        ],
        [
            "title" => "Premium Membership",
            "sections" => [
                [
                    "title" => "How does the Premium membership work?",
                    "content" => [
                        "Our Premium membership gives you access to premium leagues and tournaments.
                        We host cash prize competitions all day, every day for premium members.
                        You'll find both free-to-enter and pay-to-enter premium only competitions.
                        Check out the prize pools."
                    ]
                ],
                [
                    "title" => "How much is the Premium membership? Is there a discount?",
                    "content" => [
                        "$10/month. You maintain your membership status on your own - we won't ever auto-charge you.
                        We don't like subscriptions. (I should be clear though, if you prefer the convenience of automatic subscriptions - you can choose so at checkout).
                        If you buy up 6 or 12 months at once, we'll go aheaad and knock off 5% and 10% percent, respectively."
                    ]
                ],
                [
                    "title" => "What are the benefits to Premium?",
                    "content" => [
                        "Cash prizes - lots and lots of cash prizes. Access to our best competitions. Premium dedicated support center."
                    ]
                ]
            ]
        ],
        [
            "title" => "Rating System",
            "sections" => [
                [
                    "title" => "Why am I unranked?",
                    "content" => [
                        "You must play a minimum of [5] matches before earning rank.
                        These first 5 matches wil assess your base rank.
                        Your rank wil be revealed after the 5th match is completed."
                    ]
                ],
                [
                    "title" => "What is the Glicko2 rating System?",
                    "content" => [
                        "Our rating system is based on the Glicko2 skill based rating system.
                        Ratings are determined based on your performance against other rated players.
                        If you beat a player ranked significantly higher, you will earn more points.
                        If you beat a player ranked significantly lower, you will earn less points."
                    ]
                ],
                [
                    "title" => "Do team match results affect my individual game ranking?",
                    "content" => [
                        "Your individual rating for this leaderboard will change after singles, doubles, and team matches.
                        For doubles and team matches, we take your individual rating and match that up against an average of all individual ratings on the opposing team.
                        If you beat a team average ranked significantly higher, you will earn more points.
                        If you beat a team average ranked significantly lower, you will earn less points."
                    ]
                ]/*,
                [
                    "title" => "I'm really curious about the math - can you share details?",
                    "content" => [
                        "Sure!  Check out the public algorithm that drives our ranking system here: [LINK]
                        For details regarding team adaptation, check our this paper: [LINK]"
                    ]
                ]*/
            ]
        ],
        [
            "title" => "Roster Lock",
            "sections" => [
                [
                    "title" => "Can you explain the roster lock system?",
                    "content" => [
                        "Sure! Once you check-in to tournaments, or declare your team ready to compete in a league or ladder, we lock your team roster.
                        We do not generally allow adding or removing anyone from your team after competition has started.
                        This maintains team integrity and fair competition."
                    ]
                ],
                [
                    "title" => "Can I unlock my roster?",
                    "content" => [
                        "No. When the tournament, league, or ladder ends - your team roster will unlock automatically."
                    ]
                ],
                [
                    "title" => "A player on my team can no longer compete - can I add an alternate?",
                    "content" => [
                        "That's rough.  Blame your - now former - teammate, not us.
                        Most leagues and ladders give some flexibility for team ladders (ie you can lock 5 players and compete in a 3v3 or 4v4).
                        The vast majority of our competitions are short-lived anyway."
                    ]
                ]
            ]
        ],
        [
            "title" => "Support Center",
            "sections" => [
                [
                    "title" => "How do I create a ticket?",
                    "content" => [
                        "Check out the 'Tickets' link from inside your profile menu (click on your avatar - top right corner).
                        You can create new tickets, edit existing tickets, and view past closed tickets.
                        Please note - For match related support, use the [support button] found on the match page.
                        This will auto attach your match ID to the ticket for prompt service."
                    ]
                ],
                [
                    "title" => "How do I update a ticket?",
                    "content" => [
                        "Open the existing ticket from inside your support center.  Add any new comments or files."
                    ]
                ],
                [
                    "title" => "Where do I get help for an in-game match issue? (cheating/no-show/lag/etc.)",
                    "content" => [
                        "Go to your match page.  You'll find a [support button] you can click.  Fill out the ticket, and we'll get back ASAP."
                    ]
                ],
                [
                    "title" => "I found a site bug. How do I report it?",
                    "content" => [
                        "As much as we like to think our website is perfect, things do break sometimes.
                        Help us out by submitting a bug report ticket in the support center. Ticket category = bug."
                    ]
                ],
                [
                    "title" => "I'm not satisfied with a ticket's resolution - can I escalate?",
                    "content" => [
                        "Sure. Create a new ticket complaining about the previous ticket.
                        Our staff will love it. On a more serious note, if you are ever NOT satisfied with our support team, please email admin@gamrs.net.
                        We are very proud of our best-in-class support and will do everything in our power to help solve the problem."
                    ]
                ]
            ]
        ],
        [
            "title" => "Team Management",
            "sections" => [
                [
                    "title" => "How do I create a team?",
                    "content" => [
                        "Step 1 - pick a game
                        Step 2 - pick a platform (if req'd)
                        Step 3 - pick a competition
                        Step 4 - select the 'join'
                        Step 5 - create team"
                    ]
                ],
                [
                    "title" => "How do I add players to my team?",
                    "content" => [
                        "Step 1 - make sure you have a team created first.
                        Step 2 - select 'my teams' from your profile menu. (your avatar top right corner of screen)
                        Step 3 - gear icon, invite user to team."
                    ]
                ],
                [
                    "title" => "How do I remove players from my team?",
                    "content" => [
                        "Step 1 - make sure you have a team created first.
                        Step 2 - select 'my teams' from your profile menu. (your avatar top right corner of screen)
                        Step 3 - only players with 'modify team' enabled can remove players from team
                        Step 4 - select player you want to boot
                        Step 5 - select kick member
                        Step 6 - select update roster"
                    ]
                ],
                [
                    "title" => "Why is my team roster locked?",
                    "content" => [
                        "The roster locks whenever your team checks-in to a tournament, or declares itself ready for ladder matches.
                        No you cannot boot the guy that carried you to the top of the leaderboard.
                        No you can't add your friend for easy money towards the end of the competition."
                    ]
                ],
                [
                    "title" => "How do I give team admin permissions to someone else?",
                    "content" => [
                        "Step 1 - make sure you have a team created first.
                        Step 2 - select 'my teams' from your profile menu. (your avatar top right corner of screen)
                        Step 3 - only players with 'modify team' enabled can remove or assign admin status to other players
                        Step 4 - select player you want to promote
                        Step 5 - enable modify team
                        Step 6 - select update roster"
                    ]
                ],
                [
                    "title" => "How do I leave a team I created?",
                    "content" => [
                        "You must first give another member of the team 'modify team' privileges.
                        The new admin must remove your modify team privilege.
                        Now you can leave team - assuming the roster isn't locked."
                    ]
                ]
            ]
        ],
        [
            "title" => "Tournaments",
            "sections" => [
                [
                    "title" => "How does the check-in system work?",
                    "content" => [
                        "We had an issue in the past with no-shows.
                        Our check-in system combats that.
                        [1] hour before the tournament starts, all registered teams must check-in to reserve their spot in the bracket.
                        Your team will not be able to check-in unless the minium required players are eligible.
                        We recommend verifying eligibility before the check-in windows open.
                        This makes for an easy, seamless check-in process.
                        If there is an entry fee, all players on the team must have the funds available in their bank balance to check-in.
                        Inadequate funds will make that player ineligibile."
                    ]
                ],
                [
                    "title" => "Where is the bracket?  How does it work?",
                    "content" => [
                        "The bracket will be auto-generated when the tournament starts.
                        Your team's place in the bracket will be outlined black.
                        You may notice colored dots beside matches in the bracket.
                        Green dots represent an active, in-progress match.  Black dots represent completed matches.
                        Red dots represent a disputed match."
                    ]
                ],
                [
                    "title" => "How do I add players to my team?",
                    "content" => [
                        "You can add players to your team by sending them an invite.
                        You can search by a number of different player Ids.
                        On your team management page, you'll find a link to a menu that allows you to invite players."
                    ]
                ],
                [
                    "title" => "What is the roster lock feature?",
                    "content" => [
                        "Once you check-in to the tournament, your roster will be locked.
                        This prevents teams from adding or removing players mid-tournament.
                        We do not currently allow alternates in tournaments."
                    ]
                ],
                [
                    "title" => "Why are some of the players on my team ineligible?",
                    "content" => [
                        "Players may be ineligible for a number of reasons.
                        If they recently changed their gaming Id, they must wait a period of time before gaining eligibiltiy.
                        If they do not meet the minimum tournament requirement, they will also be ineligible.
                        Players may also be lacking the requried funds in their bank balance to pay the entry fee - also resulting in ineligibility."
                    ]
                ]
            ]
        ]
    ],
    "path" => __FILE__
];

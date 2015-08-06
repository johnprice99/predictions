<?php

namespace EatSleepCode\APIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use EatSleepCode\APIBundle\Entity\League;
use EatSleepCode\APIBundle\Entity\User;

/**
 * @Route("/league", service="api_league_controller")
 */
class LeagueController extends APIController {

    /**
     * @Route("", name="api_get_my_leagues")
     * @Method({"GET"})
     */
    public function getMyLeaguesAction(User $user) {
        $leagues = array();
        foreach ($this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findByOwner($user) as $l) {
            $leagues[] = $l->jsonEncode();
        }
        return $this->_JSONResponse($leagues);
    }

    /**
     * @Route("/{leagueId}", name="api_get_league", requirements={"leagueId": "\d+"})
     * @Method({"GET"})
     */
    public function getLeagueAction($leagueId, User $user) {
        //check that the league exists
        $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
        if ($league != null) {
            //check that the user owns the league first
            if ($league->getOwner() === $user) {
                return $this->_JSONResponse($league->jsonEncode());
            }
            return $this->_accessDenied();
        }
        return $this->_notFound();
    }

    /**
     * @Route(name="api_create_league")
     * @Method({"POST"})
     */
    public function createLeagueAction(Request $request, User $user) {
        if ($l = json_decode($request->getContent())) {
            if ($l->name == "ESC Global") {
                return $this->_badRequest("Invalid league name");
            }

            $newLeague = new League();
            $newLeague->setName($l->name);
            $newLeague->setOwner($user);

            $this->entityManager->persist($newLeague);
            $this->entityManager->flush();

            return $this->_JSONResponse($newLeague->jsonEncode(), 201);
        }
        return $this->_badRequest("Cannot Parse JSON");
    }

    /**
     * @Route("/{leagueId}", name="api_edit_league", requirements={"leagueId": "\d+"})
     * @Method({"PUT"})
     */
    public function editLeagueAction($leagueId, Request $request, User $user) {
        if ($l = json_decode($request->getContent())) {
            //check that the league exists
            $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
            if ($league != null) {
                //check that the user owns the league first
                if ($league->getOwner() === $user) {
                    $league->setName($l->name);
                    $this->entityManager->persist($league);
                    $this->entityManager->flush();

                    return $this->_JSONResponse($league->jsonEncode());
                }
                return $this->_accessDenied();
            }
            return $this->_notFound();
        }
        return $this->_badRequest("Cannot Parse JSON");
    }

    /**
     * @Route("/{leagueId}", name="api_delete_league", requirements={"leagueId": "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteLeagueAction($leagueId, User $user) {
        //check that the league exists
        $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
        if ($league != null) {
            //check that the user owns the league first
            if ($league->getOwner() === $user) {
                $this->entityManager->remove($league);
                $this->entityManager->flush();
                return $this->_JSONResponse("", 204);
            }
            return $this->_accessDenied();
        }
        return $this->_notFound();
    }

    /**
     * @Route("/join", name="api_join_league")
     * @Method({"POST"})
     */
    public function joinLeagueAction(Request $request, User $user) {
        if ($l = json_decode($request->getContent())) {
            //check that the league exists
            $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneBy(array('code' => $l->code));
            if ($league != null) {
                if ($league->hasEntrant($user)) {
                    return $this->_badRequest("User already exists in this league");
                }
                else {
                    $league->addEntrant($user);
                    $this->entityManager->persist($league);
                    $this->entityManager->flush();
                    return $this->_JSONResponse(array(
                        'message' => 'Join successful',
                        'id' => $league->getId(),
                        'name' => $league->getName(),
                    ));
                }
            }
            return $this->_notFound();
        }
        return $this->_badRequest("Cannot Parse JSON");
    }

    /**
     * @Route("/{leagueId}/leave", name="api_leave_league", requirements={"leagueId": "\d+"})
     * @Method({"POST"})
     */
    public function leaveLeagueAction($leagueId, User $user) {
        //check that the league exists
        $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
        if ($league != null) {
            //check that the user isn't the owner
            if ($league->getOwner() === $user) {
                return $this->_badRequest("The owner cannot leave the league");
            }
            if (!$league->hasEntrant($user)) {
                return $this->_badRequest("User is not in this league");
            }
            $league->removeEntrant($user);
            $this->entityManager->persist($league);
            $this->entityManager->flush();
            return $this->_JSONResponse(array('message' => 'The user has left the league'));
        }
        return $this->_notFound();
    }

    /**
     * @Route("/{leagueId}/kick/{userId}", name="api_league_kick_user", requirements={"leagueId": "\d+", "userId": "\d+"})
     * @Method({"POST"})
     */
    public function kickAction($leagueId, $userId) {
        //check that the league exists
        $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
        if ($league != null) {
            //check that the logged in user owns the league
            $user =  $this->entityManager->getRepository('EatSleepCodeAPIBundle:User')->findOneById($userId);

            //check that the user being removed isn't the owner
            if ($league->getOwner() === $user) {
                return $this->_badRequest("The owner cannot be kicked from this league");
            }
            //check that the user is part of that league
            if (!$league->hasEntrant($user)) {
                return $this->_badRequest("User is not in this league");
            }
            $league->removeEntrant($user);
            $this->entityManager->persist($league);
            $this->entityManager->flush();
            return $this->_JSONResponse(array('message' => 'The user has been kicked from the league'));
        }
        return $this->_notFound();
    }

    /**
     * @Route("/{leagueId}/standings", name="api_league_standings", requirements={"leagueId": "\d+"})
     * @Method({"GET"})
     */
    public function leagueStandingsAction($leagueId = 0, User $user) {
        //check that the league exists
        $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
        if ($leagueId == 0 || $league != null) {
            //check that this user has access to this league
            if ($leagueId != 0 && !$league->hasEntrant($user)) {
                return $this->_accessDenied();
            }

            $qb = $this->entityManager->getRepository('EatSleepCodeAPIBundle:User')
                ->createQueryBuilder('u')
                ->select(
                    array(
                        "u.id as userId",
                        "CONCAT(u.firstName, ' ', u.lastName) AS user",
                        "CONCAT('http://www.gravatar.com/avatar/', MD5(LOWER(TRIM(u.email))), '?s=100&d=mm') AS avatar",
                        "COUNT(p.id) AS predictions",
                        "SUM(CASE p.points WHEN 1 THEN 1 ELSE 0 END) AS correct",
                        "SUM(CASE p.points WHEN 3 THEN 1 ELSE 0 END) AS exact",
                        "SUM(CASE p.points WHEN 0 THEN 1 ELSE 0 END) AS wrong",
                        "COALESCE(SUM(p.points),0) AS points"
                    )
                )
                ->leftJoin('EatSleepCodeAPIBundle:Prediction', 'p', 'WITH', 'p.user = u.id')
                ->groupBy('u.id')
                ->orderBy('points', 'DESC')
                ->addOrderBy('user');

            if ($leagueId != 0) $qb->join('u.leagues', 'l', 'WITH', 'l.id = :leagueId')->setParameter('leagueId', $leagueId);

            return $this->_JSONResponse(array(
                'ownerId' => $leagueId != 0 ? $league->getOwner()->getId() : null,
                'name' => $leagueId == 0 ? 'ESC Global League' : $league->getName(),
                'code' => $leagueId != 0 ? $league->getCode() : null,
                'standings' => $qb->getQuery()->getResult()
            ));
        }
        return $this->_notFound();
    }

}

<?php

namespace EatSleepCode\APIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use EatSleepCode\APIBundle\Entity\League;
use EatSleepCode\APIBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Route("/league", service="api_league_controller")
 */
class LeagueController extends APIController {

    /**
     * @Route("", name="api_get_my_leagues")
     * @Method({"GET"})
     * @ApiDoc(
     *  resource=true,
     *  description="Retrieve a list of leagues the logged in user is a part of",
     *  statusCodes={
     *      200="JSON array of leagues"
     *  }
     * )
     */
    public function getMyLeaguesAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $leagues = array();
        foreach ($this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findByOwner($user) as $l) {
            $leagues[] = $l->jsonEncode();
        }
        return $this->_JSONResponse($leagues);
    }

    /**
     * @Route("/{leagueId}", name="api_get_league", requirements={"leagueId": "\d+"})
     * @Method({"GET"})
     * @ApiDoc(
     *  description="Retrieve a specific league which the logged in user owns",
     *  requirements={
     *      {
     *          "name"="leagueId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="The ID of the league"
     *      }
     *  },
     *  statusCodes={
     *      200="League specified by parameter",
     *      403="Logged in user is not the owner of the league",
     *      404="League does not exist",
     *  }
     * )
     */
    public function getLeagueAction($leagueId) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
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
     * Request Example:
     * ```
     * { "name": "My League" }
     * ```
     *
     * @Route(name="api_create_league")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Create a new league",
     *  parameters={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The name of the league"
     *      }
     *  },
     *  statusCodes={
     *      200="League created",
     *      400="League name is not valid, or the request JSON is invalid"
     *  }
     * )
     */
    public function createLeagueAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
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
     * Request Example:
     * ```
     * { "name": "My New League Name" }
     * ```
     *
     * @Route("/{leagueId}", name="api_edit_league", requirements={"leagueId": "\d+"})
     * @Method({"PUT"})
     * @ApiDoc(
     *  description="Rename your league",
     *  parameters={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The new name of your league"
     *      }
     *  },
     *  statusCodes={
     *      200="League renamed successfully",
     *      400="League name is not valid, or the request JSON is invalid",
     *      403="Logged in user is not the owner of the league",
     *      404="League does not exist"
     *  }
     * )
     */
    public function editLeagueAction($leagueId, Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($l = json_decode($request->getContent())) {
            //check that the league exists
            $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
            if ($league != null) {
                //check that the user owns the league first
                if ($league->getOwner() === $user) {
                    if ($l->name == "ESC Global") {
                        return $this->_badRequest("Invalid league name");
                    }
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
     * Only possible if the logged in user owns the league.
     *
     * @Route("/{leagueId}", name="api_delete_league", requirements={"leagueId": "\d+"})
     * @Method({"DELETE"})
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a league",
     *  requirements={
     *      {
     *          "name"="leagueId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="The ID of the league to remove"
     *      }
     *  },
     *  statusCodes={
     *      204="Successful deletion",
     *      403="Logged in user is not the owner of the league",
     *      404="League does not exist",
     *  }
     * )
     */
    public function deleteLeagueAction($leagueId) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
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
     * Request Example:
     * ```
     * { "code": "ABCDEF1234" }
     * ```
     *
     * @Route("/join", name="api_join_league")
     * @Method({"POST"})
     * @ApiDoc(
     *  resource=true,
     *  description="Join a league",
     *  parameters={
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The code of the league to join"
     *      }
     *  },
     *  statusCodes={
     *      200="Successfully joined the league",
     *      400="User is already in the league, or the request JSON is invalid",
     *      404="League does not exist",
     *  }
     * )
     */
    public function joinLeagueAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
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
     * @ApiDoc(
     *  resource=true,
     *  description="Leave a league",
     *  requirements={
     *      {
     *          "name"="leagueId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="The ID of the league to leave"
     *      }
     *  },
     *  statusCodes={
     *      200="Successfully left the league",
     *      400="User leaving is not in the league, or the league owner is trying to leave",
     *      404="League does not exist",
     *  }
     * )
     */
    public function leaveLeagueAction($leagueId) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
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
     * Only possible if the logged in user owns the league.
     *
     * @Route("/{leagueId}/kick/{userId}", name="api_league_kick_user", requirements={"leagueId": "\d+", "userId": "\d+"})
     * @Method({"POST"})
     * @ApiDoc(
     *  resource=true,
     *  description="Kick a user from a league",
     *  requirements={
     *      {
     *          "name"="leagueId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="The ID of the league"
     *      },
     *      {
     *          "name"="userId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="The ID of the user being removed from the league"
     *      }
     *  },
     *  statusCodes={
     *      200="Successful removal of user from the league",
     *      400="User being removed is not in the league, or trying to kick the league owner",
     *      403="Logged in user is not the owner of the league",
     *      404="League does not exist"
     *  }
     * )
     */
    public function kickAction($leagueId, $userId) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //check that the league exists
        $league = $this->entityManager->getRepository('EatSleepCodeAPIBundle:League')->findOneById($leagueId);
        if ($league != null) {
            //check that the logged in user owns the league
            if ($league->getOwner() === $user) {
                $removingUser = $this->entityManager->getRepository('EatSleepCodeAPIBundle:User')->findOneById($userId);
                //check that the user being removed isn't the owner
                if ($league->getOwner() === $removingUser) {
                    return $this->_badRequest("The owner cannot be kicked from this league");
                }
                //check that the user is part of that league
                if (!$league->hasEntrant($removingUser)) {
                    return $this->_badRequest("User is not in this league");
                }
                $league->removeEntrant($removingUser);
                $this->entityManager->persist($league);
                $this->entityManager->flush();
                return $this->_JSONResponse(array('message' => 'The user has been kicked from the league'));
            }
            return $this->_accessDenied();
        }
        return $this->_notFound();
    }

    /**
     * @Route("/{leagueId}/standings", name="api_league_standings", requirements={"leagueId": "\d+"})
     * @Method({"GET"})
     * @ApiDoc(
     *  resource=true,
     *  description="Get the standings for a given league",
     *  requirements={
     *      {
     *          "name"="leagueId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="The ID of the league"
     *      }
     *  },
     *  statusCodes={
     *      200="JSON array containing league standings",
     *      403="Logged in user is not part of the league",
     *      404="League does not exist"
     *  }
     * )
     */
    public function leagueStandingsAction($leagueId = 0) {
        $user = $this->get('security.token_storage')->getToken()->getUser();

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
